<?php

namespace Botble\Ecommerce\Http\Controllers;

use Assets;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Ecommerce\Enums\OrderReturnStatusEnum;
use Botble\Ecommerce\Http\Requests\UpdateOrderReturnRequest;
use Botble\Ecommerce\Repositories\Interfaces\OrderReturnInterface;
use Botble\Ecommerce\Repositories\Interfaces\ProductInterface;
use Botble\Ecommerce\Tables\OrderReturnTable;
use EcommerceHelper;
use Exception;
use Illuminate\Http\Request;
use OrderReturnHelper;

class OrderReturnController extends BaseController
{
    public function __construct(
        protected OrderReturnInterface $orderReturnRepository,
        protected OrderReturnInterface $orderReturnItemRepository,
        protected ProductInterface $productRepository
    ) {
    }

    public function index(OrderReturnTable $orderReturnTable)
    {
        page_title()->setTitle(trans('plugins/ecommerce::order.order_return'));

        return $orderReturnTable->renderTable();
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

        $returnRequest = $this->orderReturnRepository->findOrFail($id, ['items', 'customer', 'order']);

        page_title()->setTitle(trans('plugins/ecommerce::order.edit_order_return', ['code' => $returnRequest->code]));

        $defaultStore = get_primary_store_locator();

        return view('plugins/ecommerce::order-returns.edit', compact('returnRequest', 'defaultStore'));
    }

    public function update(int|string $id, UpdateOrderReturnRequest $request, BaseHttpResponse $response)
    {
        $returnRequest = $this->orderReturnRepository->findOrFail($id);

        $data['return_status'] = $request->input('return_status');

        if ($returnRequest->return_status == $data['return_status'] ||
            $returnRequest->return_status == OrderReturnStatusEnum::CANCELED ||
            $returnRequest->return_status == OrderReturnStatusEnum::COMPLETED) {
            return $response
                ->setError()
                ->setMessage(trans('plugins/ecommerce::order.notices.update_return_order_status_error'));
        }

        [$status, $returnRequest] = OrderReturnHelper::updateReturnOrder($returnRequest, $data);

        if (! $status) {
            return $response
                ->setError()
                ->setMessage(trans('plugins/ecommerce::order.notices.update_return_order_status_error'));
        }

        return $response
            ->setNextUrl(route('order_returns.edit', $returnRequest->id))
            ->setMessage(trans('core/base::notices.update_success_message'));
    }

    public function destroy(int|string $id, Request $request, BaseHttpResponse $response)
    {
        $order = $this->orderReturnRepository->findOrFail($id);

        try {
            $this->orderReturnRepository->deleteBy(['id' => $id]);
            event(new DeletedContentEvent(ORDER_RETURN_MODULE_SCREEN_NAME, $request, $order));

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
            $order = $this->orderReturnRepository->findOrFail($id);

            $this->orderReturnRepository->delete($order);
            event(new DeletedContentEvent(ORDER_RETURN_MODULE_SCREEN_NAME, $request, $order));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }
}
