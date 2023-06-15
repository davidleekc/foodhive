<?php

namespace Botble\Ecommerce\Http\Controllers;

use Assets;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Ecommerce\Enums\OrderStatusEnum;
use Botble\Ecommerce\Enums\ShippingMethodEnum;
use Botble\Ecommerce\Enums\ShippingStatusEnum;
use Botble\Ecommerce\Events\OrderConfirmedEvent;
use Botble\Ecommerce\Events\OrderCreated;
use Botble\Ecommerce\Events\ProductQuantityUpdatedEvent;
use Botble\Ecommerce\Facades\DiscountFacade;
use Botble\Ecommerce\Http\Requests\AddressRequest;
use Botble\Ecommerce\Http\Requests\ApplyCouponRequest;
use Botble\Ecommerce\Http\Requests\CreateOrderRequest;
use Botble\Ecommerce\Http\Requests\CreateShipmentRequest;
use Botble\Ecommerce\Http\Requests\RefundRequest;
use Botble\Ecommerce\Http\Requests\UpdateOrderRequest;
use Botble\Ecommerce\Http\Resources\OrderAddressResource;
use Botble\Ecommerce\Repositories\Interfaces\AddressInterface;
use Botble\Ecommerce\Repositories\Interfaces\CustomerInterface;
use Botble\Ecommerce\Repositories\Interfaces\OrderAddressInterface;
use Botble\Ecommerce\Repositories\Interfaces\OrderHistoryInterface;
use Botble\Ecommerce\Repositories\Interfaces\OrderInterface;
use Botble\Ecommerce\Repositories\Interfaces\OrderProductInterface;
use Botble\Ecommerce\Repositories\Interfaces\ProductInterface;
use Botble\Ecommerce\Repositories\Interfaces\ShipmentHistoryInterface;
use Botble\Ecommerce\Repositories\Interfaces\ShipmentInterface;
use Botble\Ecommerce\Repositories\Interfaces\StoreLocatorInterface;
use Botble\Ecommerce\Services\HandleApplyCouponService;
use Botble\Ecommerce\Services\HandleShippingFeeService;
use Botble\Ecommerce\Tables\OrderIncompleteTable;
use Botble\Ecommerce\Tables\OrderTable;
use Botble\Payment\Enums\PaymentMethodEnum;
use Botble\Payment\Enums\PaymentStatusEnum;
use Botble\Payment\Repositories\Interfaces\PaymentInterface;
use Carbon\Carbon;
use EcommerceHelper;
use EmailHandler;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use InvoiceHelper;
use OrderHelper;
use RvMedia;

class OrderController extends BaseController
{
    public function __construct(
        protected OrderInterface $orderRepository,
        protected CustomerInterface $customerRepository,
        protected OrderHistoryInterface $orderHistoryRepository,
        protected ProductInterface $productRepository,
        protected ShipmentInterface $shipmentRepository,
        protected OrderAddressInterface $orderAddressRepository,
        protected StoreLocatorInterface $storeLocatorRepository,
        protected OrderProductInterface $orderProductRepository,
        protected AddressInterface $addressRepository
    ) {
    }

    public function index(OrderTable $dataTable)
    {
        page_title()->setTitle(trans('plugins/ecommerce::order.menu'));

        return $dataTable->renderTable();
    }

    public function create()
    {
        Assets::addStylesDirectly(['vendor/core/plugins/ecommerce/css/ecommerce.css'])
            ->addScriptsDirectly([
                'vendor/core/plugins/ecommerce/libraries/jquery.textarea_autosize.js',
                'vendor/core/plugins/ecommerce/js/order-create.js',
            ])
            ->addScripts(['blockui', 'input-mask'])
            ->usingVueJS();

        if (EcommerceHelper::loadCountriesStatesCitiesFromPluginLocation()) {
            Assets::addScriptsDirectly('vendor/core/plugins/location/js/location.js');
        }

        page_title()->setTitle(trans('plugins/ecommerce::order.create'));

        return view('plugins/ecommerce::orders.create');
    }

    public function store(CreateOrderRequest $request, BaseHttpResponse $response)
    {
        $request->merge([
            'amount' => $request->input('amount') + $request->input('shipping_amount') - $request->input('discount_amount'),
            'user_id' => $request->input('customer_id') ?? 0,
            'shipping_method' => $request->input('shipping_method') ?: ShippingMethodEnum::DEFAULT,
            'shipping_option' => $request->input('shipping_option'),
            'shipping_amount' => $request->input('shipping_amount'),
            'tax_amount' => session('tax_amount', 0),
            'sub_total' => $request->input('amount'),
            'coupon_code' => $request->input('coupon_code'),
            'discount_amount' => $request->input('discount_amount'),
            'discount_description' => $request->input('discount_description'),
            'description' => $request->input('note'),
            'is_confirmed' => 1,
            'is_finished' => 1,
            'status' => OrderStatusEnum::PROCESSING,
        ]);

        $storeIds = [];

        foreach ($request->input('products', []) as $productItem) {
            $product = $this->productRepository->findById(Arr::get($productItem, 'id'));
            if (! $product) {
                continue;
            }

            if (is_plugin_active('marketplace') && $product->original_product->store_id && $product->original_product->store->id) {
                $storeIds[] = $product->original_product->store_id;
            }

            if (count(array_unique($storeIds)) > 1) {
                return $response
                    ->setError()
                    ->setMessage(trans('plugins/marketplace::order.products_are_from_different_vendors'));
            }

            if ($product->with_storehouse_management && $product->quantity < Arr::get($productItem, 'quantity', 1)) {
                return $response
                    ->setError()
                    ->setMessage(trans('plugins/ecommerce::order.one_or_more_products_dont_have_enough_quantity'));
            }
        }

        $order = $this->orderRepository->createOrUpdate($request->input());

        if ($order) {
            $this->orderHistoryRepository->createOrUpdate([
                'action' => 'create_order_from_payment_page',
                'description' => trans('plugins/ecommerce::order.create_order_from_payment_page'),
                'order_id' => $order->id,
            ]);

            $this->orderHistoryRepository->createOrUpdate([
                'action' => 'create_order',
                'description' => trans(
                    'plugins/ecommerce::order.new_order',
                    ['order_id' => $order->code]
                ),
                'order_id' => $order->id,
            ]);

            $this->orderHistoryRepository->createOrUpdate([
                'action' => 'confirm_order',
                'description' => trans('plugins/ecommerce::order.order_was_verified_by'),
                'order_id' => $order->id,
                'user_id' => Auth::id(),
            ]);

            if (is_plugin_active('payment')) {
                $payment = app(PaymentInterface::class)->createOrUpdate([
                    'amount' => $order->amount,
                    'currency' => cms_currency()->getDefaultCurrency()->title,
                    'payment_channel' => $request->input('payment_method'),
                    'status' => $request->input('payment_status', PaymentStatusEnum::PENDING),
                    'payment_type' => 'confirm',
                    'order_id' => $order->id,
                    'charge_id' => Str::upper(Str::random(10)),
                    'user_id' => Auth::id(),
                ]);

                $order->payment_id = $payment->id;
                $order->save();

                if ($request->input('payment_status') === PaymentStatusEnum::COMPLETED) {
                    $this->orderHistoryRepository->createOrUpdate([
                        'action' => 'confirm_payment',
                        'description' => trans('plugins/ecommerce::order.payment_was_confirmed_by', [
                            'money' => format_price($order->amount),
                        ]),
                        'order_id' => $order->id,
                        'user_id' => Auth::id(),
                    ]);
                }
            }

            if ($request->input('customer_address.name')) {
                $this->orderAddressRepository->create([
                    'name' => $request->input('customer_address.name'),
                    'phone' => $request->input('customer_address.phone'),
                    'email' => $request->input('customer_address.email'),
                    'state' => $request->input('customer_address.state'),
                    'city' => $request->input('customer_address.city'),
                    'zip_code' => $request->input('customer_address.zip_code'),
                    'country' => $request->input('customer_address.country'),
                    'address' => $request->input('customer_address.address'),
                    'order_id' => $order->id,
                ]);
            } elseif ($request->input('customer_id')) {
                $customer = $this->customerRepository->findById($request->input('customer_id'));
                $this->orderAddressRepository->create([
                    'name' => $customer->name,
                    'phone' => $customer->phone,
                    'email' => $customer->email,
                    'order_id' => $order->id,
                ]);
            }

            foreach ($request->input('products', []) as $productItem) {
                $product = $this->productRepository->findById(Arr::get($productItem, 'id'));
                if (! $product) {
                    continue;
                }

                $data = [
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'product_name' => $product->original_product->name,
                    'product_image' => $product->original_product->image,
                    'qty' => Arr::get($productItem, 'quantity', 1),
                    'weight' => $product->weight * Arr::get($productItem, 'quantity', 1),
                    'price' => $product->front_sale_price,
                    'tax_amount' => 0,
                    'options' => [],
                    'product_type' => $product->product_type,
                ];

                $this->orderProductRepository->create($data);

                $ids = [$product->id];
                if ($product->is_variation && $product->original_product) {
                    $ids[] = $product->original_product->id;
                }

                $this->productRepository
                    ->getModel()
                    ->whereIn('id', $ids)
                    ->where('with_storehouse_management', 1)
                    ->where('quantity', '>=', Arr::get($productItem, 'quantity', 1))
                    ->decrement('quantity', Arr::get($productItem, 'quantity', 1));

                event(new ProductQuantityUpdatedEvent($product));
            }

            event(new OrderCreated($order));
        }

        if ($couponCode = $request->input('coupon_code')) {
            DiscountFacade::getFacadeRoot()->afterOrderPlaced($couponCode, $request->input('customer_id'));
        }

        return $response
            ->setData($order)
            ->setMessage(trans('core/base::notices.create_success_message'));
    }

    public function edit(int|string $id)
    {
        Assets::addStylesDirectly(['vendor/core/plugins/ecommerce/css/ecommerce.css'])
            ->addScriptsDirectly([
                'vendor/core/plugins/ecommerce/libraries/jquery.textarea_autosize.js',
                'vendor/core/plugins/ecommerce/js/order.js',
            ])
            ->addScripts(['blockui', 'input-mask']);

        if (EcommerceHelper::loadCountriesStatesCitiesFromPluginLocation()) {
            Assets::addScriptsDirectly('vendor/core/plugins/location/js/location.js');
        }

        $order = $this->orderRepository->getFirstBy(['id' => $id, 'is_finished' => 1], [], ['products', 'user']);

        if (! $order) {
            abort(404);
        }

        page_title()->setTitle(trans('plugins/ecommerce::order.edit_order', ['code' => $order->code]));

        $weight = $order->products_weight;

        $defaultStore = get_primary_store_locator();

        return view('plugins/ecommerce::orders.edit', compact('order', 'weight', 'defaultStore'));
    }

    public function update(int|string $id, UpdateOrderRequest $request, BaseHttpResponse $response)
    {
        $order = $this->orderRepository->findOrFail($id);
        $order->fill($request->input());

        $order = $this->orderRepository->createOrUpdate($order);

        event(new UpdatedContentEvent(ORDER_MODULE_SCREEN_NAME, $request, $order));

        return $response
            ->setPreviousUrl(route('orders.index'))
            ->setMessage(trans('core/base::notices.update_success_message'));
    }

    public function destroy(int|string $id, Request $request, BaseHttpResponse $response)
    {
        $order = $this->orderRepository->findOrFail($id);

        try {
            $this->orderRepository->deleteBy(['id' => $id]);
            event(new DeletedContentEvent(ORDER_MODULE_SCREEN_NAME, $request, $order));

            return $response->setMessage(trans('core/base::notices.delete_success_message'));
        } catch (Exception $exception) {
            return $response
                ->setError()
                ->setMessage($exception->getMessage());
        }
    }

    public function deletes(Request $request, BaseHttpResponse $response)
    {
        $ids = $request->input('ids');
        if (empty($ids)) {
            return $response
                ->setError()
                ->setMessage(trans('core/base::notices.no_select'));
        }

        foreach ($ids as $id) {
            $order = $this->orderRepository->findOrFail($id);
            $this->orderRepository->delete($order);
            event(new DeletedContentEvent(ORDER_MODULE_SCREEN_NAME, $request, $order));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }

    public function getGenerateInvoice(int|string $orderId, Request $request)
    {
        $order = $this->orderRepository->findOrFail($orderId);

        if (! $order->isInvoiceAvailable()) {
            abort(404);
        }

        if ($request->input('type') == 'print') {
            return InvoiceHelper::streamInvoice($order->invoice);
        }

        return InvoiceHelper::downloadInvoice($order->invoice);
    }

    public function postConfirm(Request $request, BaseHttpResponse $response)
    {
        $order = $this->orderRepository->findOrFail($request->input('order_id'));
        $order->is_confirmed = 1;
        if ($order->status == OrderStatusEnum::PENDING) {
            $order->status = OrderStatusEnum::PROCESSING;
        }

        $this->orderRepository->createOrUpdate($order);

        $this->orderHistoryRepository->createOrUpdate([
            'action' => 'confirm_order',
            'description' => trans('plugins/ecommerce::order.order_was_verified_by'),
            'order_id' => $order->id,
            'user_id' => Auth::id(),
        ]);

        $payment = app(PaymentInterface::class)->getFirstBy(['order_id' => $order->id]);

        if ($payment) {
            $payment->user_id = Auth::id();
            $payment->save();
        }

        event(new OrderConfirmedEvent($order, Auth::user()));

        $mailer = EmailHandler::setModule(ECOMMERCE_MODULE_SCREEN_NAME);
        if ($mailer->templateEnabled('order_confirm')) {
            OrderHelper::setEmailVariables($order);
            $mailer->sendUsingTemplate(
                'order_confirm',
                $order->user->email ?: $order->address->email
            );
        }

        return $response->setMessage(trans('plugins/ecommerce::order.confirm_order_success'));
    }

    public function postResendOrderConfirmationEmail(int|string $id, BaseHttpResponse $response)
    {
        $order = $this->orderRepository->findOrFail($id);
        $result = OrderHelper::sendOrderConfirmationEmail($order);

        if (! $result) {
            return $response
                ->setError()
                ->setMessage(trans('plugins/ecommerce::order.error_when_sending_email'));
        }

        return $response->setMessage(trans('plugins/ecommerce::order.sent_confirmation_email_success'));
    }

    public function getShipmentForm(
        int|string $orderId,
        HandleShippingFeeService $shippingFeeService,
        Request $request,
        BaseHttpResponse $response
    ) {
        $order = $this->orderRepository->findOrFail($orderId);

        if ($request->has('weight')) {
            $weight = $request->input('weight');
        } else {
            $weight = $order->products_weight;
        }

        $shippingData = [
            'address' => $order->address->address,
            'country' => $order->address->country,
            'state' => $order->address->state,
            'city' => $order->address->city,
            'weight' => $weight,
            'order_total' => $order->amount,
        ];

        $shipping = $shippingFeeService->execute($shippingData);

        $storeLocators = $this->storeLocatorRepository->allBy(['is_shipping_location' => true]);

        $url = route('orders.create-shipment', $order->id);

        if ($request->has('view')) {
            return view(
                'plugins/ecommerce::orders.shipment-form',
                compact('order', 'weight', 'shipping', 'storeLocators', 'url')
            );
        }

        return $response->setData(view(
            'plugins/ecommerce::orders.shipment-form',
            compact('order', 'weight', 'shipping', 'storeLocators', 'url')
        )->render());
    }

    public function postCreateShipment(
        int|string $id,
        CreateShipmentRequest $request,
        BaseHttpResponse $response,
        ShipmentHistoryInterface $shipmentHistoryRepository
    ) {
        $order = $this->orderRepository->findOrFail($id);
        $result = $response;

        $shipment = [
            'order_id' => $order->id,
            'user_id' => Auth::id(),
            'weight' => $order->products_weight,
            'note' => $request->input('note'),
            'cod_amount' => $request->input('cod_amount') ?? (is_plugin_active('payment') && $order->payment->status != PaymentStatusEnum::COMPLETED ? $order->amount : 0),
            'cod_status' => 'pending',
            'type' => $request->input('method'),
            'status' => ShippingStatusEnum::DELIVERING,
            'price' => $order->shipping_amount,
            'store_id' => $request->input('store_id'),
        ];

        $store = $this->storeLocatorRepository->findById($request->input('store_id'));

        if (! $store) {
            $defaultStore = $this->storeLocatorRepository->getFirstBy(['is_primary' => true]);
            $shipment['store_id'] = $defaultStore ? $defaultStore->id : null;
        }

        $request->input('method');

        $result = $result->setMessage(trans('plugins/ecommerce::order.order_was_sent_to_shipping_team'));

        if (! $result->isError()) {
            $this->orderRepository->createOrUpdate([
                'status' => OrderStatusEnum::PROCESSING,
                'shipping_method' => $request->input('method'),
                'shipping_option' => $request->input('option'),
            ], compact('id'));

            $shipment = $this->shipmentRepository->createOrUpdate($shipment);

            $this->orderHistoryRepository->createOrUpdate([
                'action' => 'create_shipment',
                'description' => $result->getMessage() . ' ' . trans('plugins/ecommerce::order.by_username'),
                'order_id' => $id,
                'user_id' => Auth::id(),
            ]);

            $shipmentHistoryRepository->createOrUpdate([
                'action' => 'create_from_order',
                'description' => trans('plugins/ecommerce::order.shipping_was_created_from'),
                'shipment_id' => $shipment->id,
                'order_id' => $id,
                'user_id' => Auth::id(),
            ]);
        }

        return $result;
    }

    public function postCancelShipment(int|string $id, BaseHttpResponse $response)
    {
        $shipment = $this->shipmentRepository->createOrUpdate(
            ['status' => ShippingStatusEnum::CANCELED],
            compact('id')
        );

        $this->orderHistoryRepository->createOrUpdate([
            'action' => 'cancel_shipment',
            'description' => trans('plugins/ecommerce::order.shipping_was_canceled_by'),
            'order_id' => $shipment->order_id,
            'user_id' => Auth::id(),
        ]);

        return $response
            ->setData([
                'status' => ShippingStatusEnum::CANCELED,
                'status_text' => ShippingStatusEnum::CANCELED()->label(),
            ])
            ->setMessage(trans('plugins/ecommerce::order.shipping_was_canceled_success'));
    }

    public function postUpdateShippingAddress(int|string $id, AddressRequest $request, BaseHttpResponse $response)
    {
        $address = $this->orderAddressRepository->createOrUpdate($request->input(), compact('id'));

        if (! $address) {
            abort(404);
        }

        if ($address->order->status == OrderStatusEnum::CANCELED) {
            abort(401);
        }

        return $response
            ->setData([
                'line' => view('plugins/ecommerce::orders.shipping-address.line', compact('address'))->render(),
                'detail' => view('plugins/ecommerce::orders.shipping-address.detail', compact('address'))->render(),
            ])
            ->setMessage(trans('plugins/ecommerce::order.update_shipping_address_success'));
    }

    public function postCancelOrder(int|string $id, BaseHttpResponse $response)
    {
        $order = $this->orderRepository->findOrFail($id);

        if (! $order->canBeCanceledByAdmin()) {
            abort(403);
        }

        OrderHelper::cancelOrder($order);

        $this->orderHistoryRepository->createOrUpdate([
            'action' => 'cancel_order',
            'description' => trans('plugins/ecommerce::order.order_was_canceled_by'),
            'order_id' => $order->id,
            'user_id' => Auth::id(),
        ]);

        return $response->setMessage(trans('plugins/ecommerce::order.cancel_success'));
    }

    public function postConfirmPayment(int|string $id, BaseHttpResponse $response)
    {
        $order = $this->orderRepository->findOrFail($id, ['payment']);

        if ($order->status === OrderStatusEnum::PENDING) {
            $order->status = OrderStatusEnum::PROCESSING;
        }

        $this->orderRepository->createOrUpdate($order);

        OrderHelper::confirmPayment($order);

        return $response->setMessage(trans('plugins/ecommerce::order.confirm_payment_success'));
    }

    public function postRefund(int|string $id, RefundRequest $request, BaseHttpResponse $response)
    {
        $order = $this->orderRepository->findOrFail($id);
        if (is_plugin_active('payment') && $request->input('refund_amount') > ($order->payment->amount - $order->payment->refunded_amount)) {
            return $response
                ->setError()
                ->setMessage(trans('plugins/ecommerce::order.refund_amount_invalid', [
                    'price' => format_price(
                        $order->payment->amount - $order->payment->refunded_amount,
                        get_application_currency()
                    ),
                ]));
        }

        foreach ($request->input('products', []) as $productId => $quantity) {
            $orderProduct = $this->orderProductRepository->getFirstBy([
                'product_id' => $productId,
                'order_id' => $id,
            ]);

            if ($quantity > ($orderProduct->qty - $orderProduct->restock_quantity)) {
                $response
                    ->setError()
                    ->setMessage(trans('plugins/ecommerce::order.number_of_products_invalid'));

                break;
            }
        }

        $response = apply_filters(ACTION_BEFORE_POST_ORDER_REFUND_ECOMMERCE, $response, $order, $request);

        if ($response->isError()) {
            return $response;
        }

        $payment = $order->payment;
        if (! $payment) {
            return $response
                ->setError()
                ->setMessage(trans('plugins/ecommerce::order.cannot_found_payment_for_this_order'));
        }

        $refundAmount = $request->input('refund_amount');

        if ($paymentService = get_payment_is_support_refund_online($payment)) {
            $paymentResponse = (new $paymentService());
            if (method_exists($paymentService, 'setCurrency')) {
                $paymentResponse = $paymentResponse->setCurrency($payment->currency);
            }

            $optionRefunds = [
                'refund_note' => $request->input('refund_note'),
                'order_id' => $order->id,
            ];

            $paymentResponse = $paymentResponse->refundOrder($payment->charge_id, $refundAmount, $optionRefunds);

            if (Arr::get($paymentResponse, 'error', true)) {
                return $response
                    ->setError()
                    ->setMessage(Arr::get($paymentResponse, 'message', ''));
            }

            if (Arr::get($paymentResponse, 'data.refund_redirect_url')) {
                return $response
                    ->setNextUrl($paymentResponse['data']['refund_redirect_url'])
                    ->setData($paymentResponse['data'])
                    ->setMessage(Arr::get($paymentResponse, 'message', ''));
            }

            $refundData = (array)Arr::get($paymentResponse, 'data', []);

            $response->setData($refundData);

            $refundData['_data_request'] = $request->except(['_token']) + [
                    'currency' => $payment->currency,
                    'created_at' => Carbon::now(),
                ];
            $metadata = $payment->metadata;
            $refunds = Arr::get($metadata, 'refunds', []);
            $refunds[] = $refundData;
            Arr::set($metadata, 'refunds', $refunds);

            $payment->metadata = $metadata;
        }

        $payment->refunded_amount += $refundAmount;

        if ($payment->refunded_amount == $payment->amount) {
            $payment->status = PaymentStatusEnum::REFUNDED;
        }

        $payment->refund_note = $request->input('refund_note');
        app(PaymentInterface::class)->createOrUpdate($payment);

        foreach ($request->input('products', []) as $productId => $quantity) {
            $product = $this->productRepository->findById($productId);

            if ($product && $product->with_storehouse_management) {
                $product->quantity += $quantity;
                $this->productRepository->createOrUpdate($product);
            }

            $orderProduct = $this->orderProductRepository->getFirstBy([
                'product_id' => $productId,
                'order_id' => $id,
            ]);

            if ($orderProduct) {
                $orderProduct->restock_quantity += $quantity;
                $this->orderProductRepository->createOrUpdate($orderProduct);
            }
        }

        if ($refundAmount > 0) {
            $this->orderHistoryRepository->createOrUpdate([
                'action' => 'refund',
                'description' => trans('plugins/ecommerce::order.refund_success_with_price', [
                    'price' => format_price($refundAmount),
                ]),
                'order_id' => $order->id,
                'user_id' => Auth::id(),
                'extras' => json_encode([
                    'amount' => $refundAmount,
                    'method' => $payment->payment_channel ?? PaymentMethodEnum::COD,
                ]),
            ]);
        }

        $response->setMessage(trans('plugins/ecommerce::order.refund_success'));

        return apply_filters(ACTION_AFTER_POST_ORDER_REFUNDED_ECOMMERCE, $response, $order, $request);
    }

    public function getAvailableShippingMethods(
        Request $request,
        BaseHttpResponse $response,
        HandleShippingFeeService $shippingFeeService
    ) {
        $weight = 0;
        $orderAmount = 0;

        foreach ($request->input('products', []) as $productId) {
            $product = $this->productRepository->findById($productId);
            if ($product) {
                $weight += $product->weight * $product->qty;
                $orderAmount += $product->front_sale_price;
            }
        }

        $weight = EcommerceHelper::validateOrderWeight($weight);

        $shippingData = [
            'address' => $request->input('address'),
            'country' => $request->input('country'),
            'state' => $request->input('state'),
            'city' => $request->input('city'),
            'weight' => $weight,
            'order_total' => $orderAmount,
        ];

        $shipping = $shippingFeeService->execute($shippingData);

        $result = [];
        foreach ($shipping as $key => $shippingItem) {
            foreach ($shippingItem as $subKey => $subShippingItem) {
                $result[$key . ';' . $subKey . ';' . $subShippingItem['price']] = [
                    'name' => $subShippingItem['name'],
                    'price' => format_price($subShippingItem['price'], null, true),
                ];
            }
        }

        return $response->setData($result);
    }

    public function postApplyCoupon(
        ApplyCouponRequest $request,
        HandleApplyCouponService $handleApplyCouponService,
        BaseHttpResponse $response
    ) {
        $result = $handleApplyCouponService->applyCouponWhenCreatingOrderFromAdmin($request);

        if ($result['error']) {
            return $response
                ->setError()
                ->withInput()
                ->setMessage($result['message']);
        }

        return $response
            ->setData(Arr::get($result, 'data', []))
            ->setMessage(trans(
                'plugins/ecommerce::order.applied_coupon_success',
                ['code' => $request->input('coupon_code')]
            ));
    }

    public function getReorder(Request $request, BaseHttpResponse $response)
    {
        if (! $request->input('order_id')) {
            return $response
                ->setError()
                ->setNextUrl(route('orders.index'))
                ->setMessage(trans('plugins/ecommerce::order.order_is_not_existed'));
        }

        page_title()->setTitle(trans('plugins/ecommerce::order.reorder'));

        Assets::usingVueJS();

        $order = $this->orderRepository->findById($request->input('order_id'));

        if (! $order) {
            return $response
                ->setError()
                ->setNextUrl(route('orders.index'))
                ->setMessage(trans('plugins/ecommerce::order.order_is_not_existed'));
        }

        $productIds = $order->products->pluck('product_id')->all();

        $products = $this->productRepository
            ->getModel()
            ->whereIn('id', $productIds)
            ->get();

        foreach ($products as &$availableProduct) {
            $availableProduct->product_name = $availableProduct->original_product->name;
            $availableProduct->image_url = RvMedia::getImageUrl(
                Arr::first($availableProduct->images ?: $availableProduct->original_product->images) ?? null,
                'thumb',
                false,
                RvMedia::getDefaultImage()
            );
            $availableProduct->price = $availableProduct->front_sale_price;
            $availableProduct->product_link = route('products.edit', $availableProduct->original_product->id);
            $availableProduct->select_qty = 1;
            $availableProduct->product_id = $availableProduct->id;

            if (is_plugin_active('marketplace') && $availableProduct->original_product->store_id && $availableProduct->original_product->store->name) {
                $availableProduct->product_name = $availableProduct->product_name . ' (' . $availableProduct->original_product->store->name . ')';
            }

            $orderProduct = $order->products->where('product_id', $availableProduct->id)->first();

            if ($orderProduct) {
                $availableProduct->select_qty = $orderProduct->qty;
            }

            $availableProduct->variations = $availableProduct->original_product->variations;

            foreach ($availableProduct->variations as &$variation) {
                $variation->price = $variation->product->front_sale_price;
                foreach ($variation->variationItems as &$variationItem) {
                    $variationItem->attribute_title = $variationItem->attribute->title;
                }
            }
        }

        $customer = null;
        $customerAddresses = [];
        $customerOrderNumbers = 0;
        if ($order->user_id) {
            $customer = $this->customerRepository->findById($order->user_id);
            $customer->avatar = (string)$customer->avatar_url;

            if ($customer) {
                $customerOrderNumbers = $customer->orders()->count();
            }

            $customerAddresses = OrderAddressResource::collection($customer->addresses);
        }

        $customerAddress = $order->address;

        $customerAddress->city_name = $order->address->city_name;
        $customerAddress->state_name = $order->address->state_name;
        $customerAddress->country_name = $order->address->country_name;

        Assets::addStylesDirectly(['vendor/core/plugins/ecommerce/css/ecommerce.css'])
            ->addScriptsDirectly([
                'vendor/core/plugins/ecommerce/libraries/jquery.textarea_autosize.js',
                'vendor/core/plugins/ecommerce/js/order-create.js',
            ])
            ->addScripts(['blockui', 'input-mask']);

        return view('plugins/ecommerce::orders.reorder', compact(
            'order',
            'products',
            'productIds',
            'customer',
            'customerAddresses',
            'customerAddress',
            'customerOrderNumbers'
        ));
    }

    public function getIncompleteList(OrderIncompleteTable $dataTable)
    {
        page_title()->setTitle(trans('plugins/ecommerce::order.incomplete_order'));

        return $dataTable->renderTable();
    }

    public function getViewIncompleteOrder(int|string $id)
    {
        page_title()->setTitle(trans('plugins/ecommerce::order.incomplete_order'));

        Assets::addStylesDirectly(['vendor/core/plugins/ecommerce/css/ecommerce.css'])
            ->addScriptsDirectly([
                'vendor/core/plugins/ecommerce/libraries/jquery.textarea_autosize.js',
                'vendor/core/plugins/ecommerce/js/order-incomplete.js',
            ]);

        $order = $this->orderRepository
            ->getModel()
            ->where('id', $id)
            ->where('is_finished', 0)
            ->with(['products', 'user'])
            ->firstOrFail();

        return view('plugins/ecommerce::orders.view-incomplete-order', compact('order'));
    }

    public function postSendOrderRecoverEmail(int|string $id, BaseHttpResponse $response)
    {
        $order = $this->orderRepository->findOrFail($id);

        $email = $order->user->email ?: $order->address->email;

        if (! $email) {
            return $response
                ->setError()
                ->setMessage(trans('plugins/ecommerce::order.error_when_sending_email'));
        }

        try {
            $mailer = EmailHandler::setModule(ECOMMERCE_MODULE_SCREEN_NAME);

            $order->dont_show_order_info_in_product_list = true;
            OrderHelper::setEmailVariables($order);

            $mailer->sendUsingTemplate('order_recover', $email);

            return $response->setMessage(trans('plugins/ecommerce::order.sent_email_incomplete_order_success'));
        } catch (Exception $exception) {
            return $response
                ->setError()
                ->setMessage($exception->getMessage());
        }
    }
}
