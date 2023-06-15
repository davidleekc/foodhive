<?php

namespace Botble\Ecommerce\Http\Controllers;

use Assets;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Ecommerce\Enums\ShippingCodStatusEnum;
use Botble\Ecommerce\Enums\ShippingStatusEnum;
use Botble\Ecommerce\Events\ShippingStatusChanged;
use Botble\Ecommerce\Http\Requests\UpdateShipmentCodStatusRequest;
use Botble\Ecommerce\Http\Requests\UpdateShipmentStatusRequest;
use Botble\Ecommerce\Repositories\Interfaces\OrderHistoryInterface;
use Botble\Ecommerce\Repositories\Interfaces\OrderInterface;
use Botble\Ecommerce\Repositories\Interfaces\ShipmentHistoryInterface;
use Botble\Ecommerce\Repositories\Interfaces\ShipmentInterface;
use Botble\Ecommerce\Tables\ShipmentTable;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use OrderHelper;

class ShipmentController extends BaseController
{
    public function __construct(
        protected OrderInterface $orderRepository,
        protected ShipmentInterface $shipmentRepository,
        protected OrderHistoryInterface $orderHistoryRepository,
        protected ShipmentHistoryInterface $shipmentHistoryRepository
    ) {
    }

    public function index(ShipmentTable $dataTable)
    {
        page_title()->setTitle(trans('plugins/ecommerce::shipping.shipments'));

        return $dataTable->renderTable();
    }

    public function edit(int|string $id)
    {
        Assets::addStylesDirectly('vendor/core/plugins/ecommerce/css/ecommerce.css')
            ->addScriptsDirectly('vendor/core/plugins/ecommerce/js/shipment.js');

        $shipment = $this->shipmentRepository->findOrFail($id);
        page_title()->setTitle(trans('plugins/ecommerce::shipping.edit_shipping', ['code' => get_shipment_code($id)]));

        return view('plugins/ecommerce::shipments.edit', compact('shipment'));
    }

    public function postUpdateStatus(int|string $id, UpdateShipmentStatusRequest $request, BaseHttpResponse $response)
    {
        $shipment = $this->shipmentRepository->findOrFail($id);
        $previousShipment = $shipment->toArray();
        $shipment->status = $request->input('status');
        $shipment->save();

        $this->shipmentHistoryRepository->createOrUpdate([
            'action' => 'update_status',
            'description' => trans('plugins/ecommerce::shipping.changed_shipping_status', [
                'status' => $shipment->status->label(),
            ]),
            'shipment_id' => $id,
            'order_id' => $shipment->order_id,
            'user_id' => Auth::id() ?? 0,
        ]);

        switch ($shipment->status) {
            case ShippingStatusEnum::DELIVERED:
                $shipment->date_shipped = Carbon::now();
                $shipment->save();

                OrderHelper::shippingStatusDelivered($shipment, $request, Auth::id() ?? 0);

                break;

            case ShippingStatusEnum::CANCELED:
                $this->orderHistoryRepository->createOrUpdate([
                    'action' => 'cancel_shipment',
                    'description' => trans('plugins/ecommerce::shipping.shipping_canceled_by'),
                    'order_id' => $shipment->order_id,
                    'user_id' => Auth::id(),
                ]);

                break;
        }

        event(new ShippingStatusChanged($shipment, $previousShipment));

        return $response->setMessage(trans('plugins/ecommerce::shipping.update_shipping_status_success'));
    }

    public function postUpdateCodStatus(int|string $id, UpdateShipmentCodStatusRequest $request, BaseHttpResponse $response)
    {
        $shipment = $this->shipmentRepository->findOrFail($id);
        $shipment->cod_status = $request->input('status');
        $shipment->save();

        if ($shipment->cod_status == ShippingCodStatusEnum::COMPLETED) {
            OrderHelper::confirmPayment($shipment->order);
        }

        $this->shipmentHistoryRepository->createOrUpdate([
            'action' => 'update_cod_status',
            'description' => trans('plugins/ecommerce::shipping.updated_cod_status_by', [
                'status' => $shipment->cod_status->label(),
            ]),
            'shipment_id' => $id,
            'order_id' => $shipment->order_id,
            'user_id' => Auth::id() ?? 0,
        ]);

        return $response->setMessage(trans('plugins/ecommerce::shipping.update_cod_status_success'));
    }

    public function update(int|string $id, Request $request, BaseHttpResponse $response)
    {
        $shipment = $this->shipmentRepository->findOrFail($id);

        $shipment->fill(
            $request->only([
                'tracking_id',
                'shipping_company_name',
                'tracking_link',
                'estimate_date_shipped',
                'note',
            ])
        );

        $this->shipmentRepository->createOrUpdate($shipment);

        return $response
            ->setPreviousUrl(route('ecommerce.shipments.index'))
            ->setMessage(trans('core/base::notices.update_success_message'));
    }

    public function destroy(int|string $id, BaseHttpResponse $response)
    {
        try {
            $review = $this->shipmentRepository->findOrFail($id);
            $this->shipmentRepository->delete($review);

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
            $review = $this->shipmentRepository->findOrFail($id);
            $this->shipmentRepository->delete($review);
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }
}
