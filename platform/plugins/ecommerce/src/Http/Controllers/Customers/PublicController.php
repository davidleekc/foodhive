<?php

namespace Botble\Ecommerce\Http\Controllers\Customers;

use Arr;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Ecommerce\Enums\OrderStatusEnum;
use Botble\Ecommerce\Enums\ProductTypeEnum;
use Botble\Ecommerce\Http\Requests\AddressRequest;
use Botble\Ecommerce\Http\Requests\AvatarRequest;
use Botble\Ecommerce\Http\Requests\EditAccountRequest;
use Botble\Ecommerce\Http\Requests\OrderReturnRequest;
use Botble\Ecommerce\Http\Requests\UpdatePasswordRequest;
use Botble\Ecommerce\Repositories\Interfaces\AddressInterface;
use Botble\Ecommerce\Repositories\Interfaces\CustomerInterface;
use Botble\Ecommerce\Repositories\Interfaces\OrderHistoryInterface;
use Botble\Ecommerce\Repositories\Interfaces\OrderInterface;
use Botble\Ecommerce\Repositories\Interfaces\OrderProductInterface;
use Botble\Ecommerce\Repositories\Interfaces\OrderReturnInterface;
use Botble\Ecommerce\Repositories\Interfaces\ProductInterface;
use Botble\Ecommerce\Repositories\Interfaces\ReviewInterface;
use Botble\Media\Services\ThumbnailService;
use Botble\Media\Supports\Zipper;
use Botble\Payment\Enums\PaymentStatusEnum;
use Carbon\Carbon;
use EcommerceHelper;
use Exception;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use InvoiceHelper;
use OrderHelper;
use OrderReturnHelper;
use RvMedia;
use SeoHelper;
use Theme;

class PublicController extends Controller
{
    public function __construct(
        protected CustomerInterface $customerRepository,
        protected ProductInterface $productRepository,
        protected AddressInterface $addressRepository,
        protected OrderInterface $orderRepository,
        protected OrderHistoryInterface $orderHistoryRepository,
        protected OrderReturnInterface $orderReturnRepository,
        protected OrderProductInterface $orderProductRepository,
        protected ReviewInterface $reviewRepository
    ) {
        Theme::asset()
            ->add('customer-style', 'vendor/core/plugins/ecommerce/css/customer.css');

        Theme::asset()
            ->container('footer')
            ->add('ecommerce-utilities-js', 'vendor/core/plugins/ecommerce/js/utilities.js', ['jquery'])
            ->add('cropper-js', 'vendor/core/plugins/ecommerce/libraries/cropper.js', ['jquery'])
            ->add('avatar-js', 'vendor/core/plugins/ecommerce/js/avatar.js', ['jquery']);

        if (EcommerceHelper::loadCountriesStatesCitiesFromPluginLocation()) {
            Theme::asset()
                ->container('footer')
                ->add('location-js', 'vendor/core/plugins/location/js/location.js', ['jquery']);
        }
    }

    public function getOverview()
    {
        SeoHelper::setTitle(__('Account information'));

        Theme::breadcrumb()
            ->add(__('Home'), route('public.index'))
            ->add(__('Account information'), route('customer.overview'));

        return Theme::scope('ecommerce.customers.overview', [], 'plugins/ecommerce::themes.customers.overview')
            ->render();
    }

    public function getEditAccount()
    {
        SeoHelper::setTitle(__('Profile'));

        Theme::asset()
            ->add(
                'datepicker-style',
                'vendor/core/core/base/libraries/bootstrap-datepicker/css/bootstrap-datepicker3.min.css',
                ['bootstrap']
            );
        Theme::asset()
            ->container('footer')
            ->add(
                'datepicker-js',
                'vendor/core/core/base/libraries/bootstrap-datepicker/js/bootstrap-datepicker.min.js',
                ['jquery']
            );

        Theme::breadcrumb()
            ->add(__('Home'), route('public.index'))
            ->add(__('Profile'), route('customer.edit-account'));

        return Theme::scope('ecommerce.customers.edit-account', [], 'plugins/ecommerce::themes.customers.edit-account')
            ->render();
    }

    public function postEditAccount(EditAccountRequest $request, BaseHttpResponse $response)
    {
        $customer = auth('customer')->user();
        $customer->fill($request->except('email'));
        $customer->dob = Carbon::parse($request->input('dob'))->toDateString();
        $customer->save();

        do_action(HANDLE_CUSTOMER_UPDATED_ECOMMERCE, $customer, $request);

        return $response
            ->setNextUrl(route('customer.edit-account'))
            ->setMessage(__('Update profile successfully!'));
    }

    public function getChangePassword()
    {
        SeoHelper::setTitle(__('Change Password'));

        Theme::breadcrumb()->add(__('Home'), route('public.index'))
            ->add(__('Change Password'), route('customer.change-password'));

        return Theme::scope(
            'ecommerce.customers.change-password',
            [],
            'plugins/ecommerce::themes.customers.change-password'
        )->render();
    }

    public function postChangePassword(UpdatePasswordRequest $request, BaseHttpResponse $response)
    {
        $currentUser = auth('customer')->user();

        if (! Hash::check($request->input('old_password'), $currentUser->getAuthPassword())) {
            return $response
                ->setError()
                ->setMessage(trans('acl::users.current_password_not_valid'));
        }

        $this->customerRepository->update(['id' => auth('customer')->id()], [
            'password' => Hash::make($request->input('password')),
        ]);

        return $response->setMessage(trans('acl::users.password_update_success'));
    }

    public function getListOrders(Request $request)
    {
        SeoHelper::setTitle(__('Orders'));

        $orders = $this->orderRepository->advancedGet([
            'condition' => [
                'user_id' => auth('customer')->id(),
                'is_finished' => 1,
            ],
            'paginate' => [
                'per_page' => 10,
                'current_paged' => (int)$request->input('page'),
            ],
            'withCount' => ['products'],
            'order_by' => ['created_at' => 'DESC'],
        ]);

        Theme::breadcrumb()
            ->add(__('Home'), route('public.index'))
            ->add(__('Orders'), route('customer.orders'));

        return Theme::scope(
            'ecommerce.customers.orders.list',
            compact('orders'),
            'plugins/ecommerce::themes.customers.orders.list'
        )->render();
    }

    public function getViewOrder(int|string $id)
    {
        $order = $this->orderRepository->getFirstBy(
            [
                'id' => $id,
                'user_id' => auth('customer')->id(),
            ],
            ['ec_orders.*'],
            ['address', 'products']
        );

        if (! $order) {
            abort(404);
        }

        SeoHelper::setTitle(__('Order detail :id', ['id' => $order->code]));

        Theme::breadcrumb()->add(__('Home'), route('public.index'))
            ->add(
                __('Order detail :id', ['id' => $order->code]),
                route('customer.orders.view', $id)
            );

        return Theme::scope(
            'ecommerce.customers.orders.view',
            compact('order'),
            'plugins/ecommerce::themes.customers.orders.view'
        )->render();
    }

    public function getCancelOrder(int|string $id, BaseHttpResponse $response)
    {
        $order = $this->orderRepository->getFirstBy([
            'id' => $id,
            'user_id' => auth('customer')->id(),
        ], ['*']);

        if (! $order) {
            abort(404);
        }

        if (! $order->canBeCanceled()) {
            return $response->setError()
                ->setMessage(trans('plugins/ecommerce::order.cancel_error'));
        }

        OrderHelper::cancelOrder($order);

        $this->orderHistoryRepository->createOrUpdate([
            'action' => 'cancel_order',
            'description' => __('Order was cancelled by custom :customer', ['customer' => $order->address->name]),
            'order_id' => $order->id,
        ]);

        return $response->setMessage(trans('plugins/ecommerce::order.cancel_success'));
    }

    public function getListAddresses(Request $request)
    {
        SeoHelper::setTitle(__('Address books'));

        $addresses = $this->addressRepository->advancedGet([
            'condition' => [
                'customer_id' => auth('customer')->id(),
            ],
            'order_by' => [
                'is_default' => 'DESC',
                'created_at' => 'DESC',
            ],
            'paginate' => [
                'per_page' => 10,
                'current_paged' => (int)$request->input('page', 1),
            ],
        ]);

        Theme::breadcrumb()
            ->add(__('Home'), route('public.index'))
            ->add(__('Address books'), route('customer.address'));

        return Theme::scope(
            'ecommerce.customers.address.list',
            compact('addresses'),
            'plugins/ecommerce::themes.customers.address.list'
        )->render();
    }

    public function getCreateAddress()
    {
        SeoHelper::setTitle(__('Create Address'));

        Theme::breadcrumb()
            ->add(__('Home'), route('public.index'))
            ->add(__('Address books'), route('customer.address'))
            ->add(__('Create Address'), route('customer.address.create'));

        return Theme::scope(
            'ecommerce.customers.address.create',
            [],
            'plugins/ecommerce::themes.customers.address.create'
        )->render();
    }

    public function postCreateAddress(AddressRequest $request, BaseHttpResponse $response)
    {
        if ($request->input('is_default') == 1) {
            $this->addressRepository->update([
                'is_default' => 1,
                'customer_id' => auth('customer')->id(),
            ], ['is_default' => 0]);
        }

        $request->merge([
            'customer_id' => auth('customer')->id(),
            'is_default' => $request->input('is_default', 0),
        ]);

        $address = $this->addressRepository->createOrUpdate($request->input());

        return $response
            ->setData([
                'id' => $address->id,
                'html' => view(
                    'plugins/ecommerce::orders.partials.address-item',
                    compact('address')
                )->render(),
            ])
            ->setNextUrl(route('customer.address'))
            ->setMessage(trans('core/base::notices.create_success_message'));
    }

    public function getEditAddress(int|string $id)
    {
        SeoHelper::setTitle(__('Edit Address #:id', ['id' => $id]));

        $address = $this->addressRepository->getFirstBy([
            'id' => $id,
            'customer_id' => auth('customer')->id(),
        ]);

        if (! $address) {
            abort(404);
        }

        Theme::breadcrumb()->add(__('Home'), route('public.index'))
            ->add(__('Edit Address #:id', ['id' => $id]), route('customer.address.edit', $id));

        return Theme::scope(
            'ecommerce.customers.address.edit',
            compact('address'),
            'plugins/ecommerce::themes.customers.address.edit'
        )->render();
    }

    public function getDeleteAddress(int|string $id, BaseHttpResponse $response)
    {
        $this->addressRepository->deleteBy([
            'id' => $id,
            'customer_id' => auth('customer')->id(),
        ]);

        return $response->setNextUrl(route('customer.address'))
            ->setMessage(trans('core/base::notices.delete_success_message'));
    }

    public function postEditAddress(int|string $id, AddressRequest $request, BaseHttpResponse $response)
    {
        if ($request->input('is_default')) {
            $this->addressRepository->update([
                'is_default' => 1,
                'customer_id' => auth('customer')->id(),
            ], ['is_default' => 0]);
        }

        $address = $this->addressRepository->createOrUpdate($request->input(), [
            'id' => $id,
            'customer_id' => auth('customer')->id(),
        ]);

        return $response
            ->setData([
                'id' => $address->id,
                'html' => view('plugins/ecommerce::orders.partials.address-item', compact('address'))
                    ->render(),
            ])
            ->setMessage(trans('core/base::notices.update_success_message'));
    }

    public function getPrintOrder(int|string $id, Request $request)
    {
        $order = $this->orderRepository->getFirstBy([
            'id' => $id,
            'user_id' => auth('customer')->id(),
        ]);

        if (! $order || ! $order->isInvoiceAvailable()) {
            abort(404);
        }

        if ($request->input('type') == 'print') {
            return InvoiceHelper::streamInvoice($order->invoice);
        }

        return InvoiceHelper::downloadInvoice($order->invoice);
    }

    public function postAvatar(AvatarRequest $request, ThumbnailService $thumbnailService, BaseHttpResponse $response)
    {
        try {
            $account = auth('customer')->user();

            $result = RvMedia::handleUpload($request->file('avatar_file'), 0, $account->upload_folder);

            if ($result['error']) {
                return $response->setError()->setMessage($result['message']);
            }

            $avatarData = json_decode($request->input('avatar_data'));

            $file = $result['data'];

            $thumbnailService
                ->setImage(RvMedia::getRealPath($file->url))
                ->setSize((int)$avatarData->width, (int)$avatarData->height)
                ->setCoordinates((int)$avatarData->x, (int)$avatarData->y)
                ->setDestinationPath(File::dirname($file->url))
                ->setFileName(File::name($file->url) . '.' . File::extension($file->url))
                ->save('crop');

            $account->avatar = $file->url;

            $this->customerRepository->createOrUpdate($account);

            return $response
                ->setMessage(trans('plugins/customer::dashboard.update_avatar_success'))
                ->setData(['url' => RvMedia::url($file->url)]);
        } catch (Exception $exception) {
            return $response
                ->setError()
                ->setMessage($exception->getMessage());
        }
    }

    public function getReturnOrder(int|string $orderId)
    {
        $order = $this->orderRepository->getFirstBy(
            [
                'id' => $orderId,
                'user_id' => auth('customer')->id(),
                'status' => OrderStatusEnum::COMPLETED,
            ],
            ['ec_orders.*'],
            ['products']
        );

        if (! $order || ! $order->canBeReturned()) {
            abort(404);
        }

        SeoHelper::setTitle(__('Request Return Product(s) In Order :id', ['id' => $order->code]));

        Theme::breadcrumb()->add(__('Home'), route('public.index'))
            ->add(
                __('Request Return Product(s) In Order :id', ['id' => $order->code]),
                route('customer.order_returns.request_view', $orderId)
            );

        Theme::asset()->container('footer')->add(
            'order-return-js',
            'vendor/core/plugins/ecommerce/js/order-return.js',
            ['jquery']
        );
        Theme::asset()->add('order-return-css', 'vendor/core/plugins/ecommerce/css/order-return.css');

        return Theme::scope(
            'ecommerce.customers.order-returns.view',
            compact('order'),
            'plugins/ecommerce::themes.customers.order-returns.view'
        )->render();
    }

    public function postReturnOrder(OrderReturnRequest $request, BaseHttpResponse $response)
    {
        $order = $this->orderRepository->getFirstBy([
            'id' => $request->input('order_id'),
            'user_id' => auth('customer')->id(),
        ]);

        if (! $order) {
            abort(404);
        }

        if (! $order->canBeReturned()) {
            return $response
                ->setError()
                ->withInput()
                ->setMessage(trans('plugins/ecommerce::order.return_error'));
        }

        $orderReturnData['reason'] = $request->input('reason');

        $orderReturnData['items'] = Arr::where($request->input(['return_items']), function ($value) {
            return isset($value['is_return']);
        });

        if (empty($orderReturnData['items'])) {
            return $response
                ->setError()
                ->withInput()
                ->setMessage(__('Please select at least 1 product to return!'));
        }

        $totalRefundAmount = $order->amount - $order->shipping_amount;
        $totalPriceProducts = $order->products->sum(function ($item) {
            return $item->total_price_with_tax;
        });
        $ratio = $totalRefundAmount <= 0 ? 0 : $totalPriceProducts / $totalRefundAmount;

        foreach ($orderReturnData['items'] as &$item) {
            $orderProductId = Arr::get($item, 'order_item_id');
            if (! $orderProduct = $order->products->firstWhere('id', $orderProductId)) {
                return $response
                    ->setError()
                    ->withInput()
                    ->setMessage(__('Opps!'));
            }
            $qty = $orderProduct->qty;
            if (EcommerceHelper::allowPartialReturn()) {
                $qty = (int) Arr::get($item, 'qty') ?: $qty;
                $qty = $qty > $orderProduct->qty ? $orderProduct->qty : $qty;
            }
            $item['qty'] = $qty;
            $item['refund_amount'] = $ratio == 0 ? 0 : ($orderProduct->price_with_tax * $qty / $ratio);
        }

        [$status, $data, $message] = OrderReturnHelper::returnOrder($order, $orderReturnData);

        if (! $status) {
            return $response
                ->setError()
                ->withInput()
                ->setMessage($message ?: trans('plugins/ecommerce::order.return_error'));
        }

        $this->orderHistoryRepository->createOrUpdate([
            'action' => 'return_order',
            'description' => __(':customer has requested return product(s)', ['customer' => $order->address->name]),
            'order_id' => $order->id,
        ]);

        return $response
            ->setMessage(trans('plugins/ecommerce::order.return_success'))
            ->setNextUrl(route('customer.order_returns.detail', ['id' => $data->id]));
    }

    public function getListReturnOrders(Request $request)
    {
        SeoHelper::setTitle(__('Order Return Requests'));

        $requests = $this->orderReturnRepository->advancedGet([
            'condition' => [
                'user_id' => auth('customer')->id(),
            ],
            'paginate' => [
                'per_page' => 10,
                'current_paged' => (int)$request->input('page'),
            ],
            'withCount' => ['items'],
            'order_by' => ['created_at' => 'DESC'],
        ]);

        Theme::breadcrumb()
            ->add(__('Home'), route('public.index'))
            ->add(__('Order Return Requests'), route('customer.order_returns'));

        return Theme::scope(
            'ecommerce.customers.order-returns.list',
            compact('requests'),
            'plugins/ecommerce::themes.customers.orders.returns.list'
        )->render();
    }

    public function getDetailReturnOrder(int|string $id)
    {
        SeoHelper::setTitle(__('Order Return Requests'));

        $orderReturn = $this->orderReturnRepository->getFirstBy([
            'id' => $id,
            'user_id' => auth('customer')->id(),
        ]);

        if (! $orderReturn) {
            abort(404);
        }

        Theme::breadcrumb()
            ->add(__('Home'), route('public.index'))
            ->add(__('Order Return Requests'), route('customer.order_returns'))
            ->add(
                __('Order Return Requests :id', ['id' => $orderReturn->id]),
                route('customer.order_returns.detail', $orderReturn->id)
            );

        return Theme::scope(
            'ecommerce.customers.order-returns.detail',
            compact('orderReturn'),
            'plugins/ecommerce::themes.customers.order-returns.detail'
        )->render();
    }

    public function getDownloads()
    {
        if (! EcommerceHelper::isEnabledSupportDigitalProducts()) {
            abort(404);
        }

        SeoHelper::setTitle(__('Downloads'));

        $orderProducts = $this->orderProductRepository->getModel()
            ->whereHas('order', function ($query) {
                $query->where([
                    'user_id' => auth('customer')->id(),
                    'is_finished' => 1,
                ]);
            })
            ->whereHas('order.payment', function ($query) {
                $query->where(['status' => PaymentStatusEnum::COMPLETED]);
            })
            ->where('product_type', ProductTypeEnum::DIGITAL)
            ->orderBy('created_at', 'desc')
            ->with(['order', 'product'])
            ->paginate(10);

        Theme::breadcrumb()
            ->add(__('Home'), route('public.index'))
            ->add(__('Downloads'), route('customer.downloads'));

        return Theme::scope(
            'ecommerce.customers.orders.downloads',
            compact('orderProducts'),
            'plugins/ecommerce::themes.customers.orders.downloads'
        )->render();
    }

    public function getDownload(int|string $id, Request $request, BaseHttpResponse $response)
    {
        if (! EcommerceHelper::isEnabledSupportDigitalProducts()) {
            abort(404);
        }

        $orderProduct = $this->orderProductRepository
            ->getModel()
            ->where([
                'id' => $id,
                'product_type' => ProductTypeEnum::DIGITAL,
            ])
            ->whereHas('order', function ($query) {
                $query->where(['is_finished' => 1]);
            })
            ->whereHas('order.payment', function ($query) {
                $query->where(['status' => PaymentStatusEnum::COMPLETED]);
            })
            ->with(['order', 'product'])
            ->first();

        if (! $orderProduct) {
            abort(404);
        }

        $order = $orderProduct->order;

        if (auth('customer')->check()) {
            if ($order->user_id != auth('customer')->id()) {
                abort(404);
            }
        } elseif (($hash = $request->input('hash'))) {
            $response->setNextUrl(route('public.index'));
            if (! $orderProduct->download_token || ! Hash::check($orderProduct->download_token, $hash)) {
                abort(404);
            }
        } else {
            abort(404);
        }

        $zipName = Str::slug($orderProduct->product_name) . Str::random(5) . '-' . Carbon::now(
        )->format('Y-m-d-h-i-s') . '.zip';
        $fileName = RvMedia::getRealPath($zipName);
        $zip = new Zipper();
        $zip->make($fileName);
        $product = $orderProduct->product;
        $productFiles = $product->id ? $product->productFiles : $orderProduct->productFiles;

        if (! $productFiles->count()) {
            return $response->setError()->setMessage(__('Cannot found files'));
        }
        foreach ($productFiles as $file) {
            $filePath = RvMedia::getRealPath($file->url);
            if (! RvMedia::isUsingCloud()) {
                if (File::exists($filePath)) {
                    $zip->add($filePath);
                }
            } else {
                $zip->addString(
                    $file->file_name,
                    file_get_contents(str_replace('https://', 'http://', $filePath))
                );
            }
        }

        if (version_compare(phpversion(), '8.0') >= 0) {
            $zip = null;
        } else {
            $zip->close();
        }

        if (File::exists($fileName)) {
            $orderProduct->increment('times_downloaded');

            return response()->download($fileName)->deleteFileAfterSend();
        }

        return $response->setError()->setMessage(__('Cannot download files'));
    }

    public function getProductReviews()
    {
        if (! EcommerceHelper::isReviewEnabled()) {
            abort(404);
        }

        SeoHelper::setTitle(__('Product Reviews'));

        Theme::asset()
            ->add('ecommerce-review-css', 'vendor/core/plugins/ecommerce/css/review.css');
        Theme::asset()->container('footer')
            ->add('ecommerce-review-js', 'vendor/core/plugins/ecommerce/js/review.js', ['jquery']);

        $customerId = auth('customer')->id();

        $reviews = $this->reviewRepository
            ->getModel()
            ->where('customer_id', $customerId)
            ->whereHas('product', function ($query) {
                $query->where('status', BaseStatusEnum::PUBLISHED);
            })
            ->with(['product', 'product.slugable'])
            ->orderBy('ec_reviews.created_at', 'desc')
            ->paginate(12);

        $products = $this->productRepository->productsNeedToReviewByCustomer($customerId);

        Theme::breadcrumb()
            ->add(__('Home'), route('public.index'))
            ->add(__('Product Reviews'), route('customer.product-reviews'));

        return Theme::scope(
            'ecommerce.customers.product-reviews.list',
            compact('products', 'reviews'),
            'plugins/ecommerce::themes.customers.product-reviews.list'
        )->render();
    }
}
