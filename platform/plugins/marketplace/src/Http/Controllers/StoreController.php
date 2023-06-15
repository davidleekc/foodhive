<?php

namespace Botble\Marketplace\Http\Controllers;

use Botble\Base\Events\BeforeEditContentEvent;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Forms\FormBuilder;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Marketplace\Forms\StoreForm;
use Botble\Marketplace\Http\Requests\StoreRequest;
use Botble\Marketplace\Repositories\Interfaces\RevenueInterface;
use Botble\Marketplace\Repositories\Interfaces\StoreInterface;
use Botble\Marketplace\Tables\StoreTable;
use Exception;
use Illuminate\Http\Request;

class StoreController extends BaseController
{
    public function __construct(protected StoreInterface $storeRepository, protected RevenueInterface $revenueRepository)
    {
    }

    public function index(StoreTable $table)
    {
        page_title()->setTitle(trans('plugins/marketplace::store.name'));

        return $table->renderTable();
    }

    public function create(FormBuilder $formBuilder)
    {
        page_title()->setTitle(trans('plugins/marketplace::store.create'));

        return $formBuilder->create(StoreForm::class)->renderForm();
    }

    public function store(StoreRequest $request, BaseHttpResponse $response)
    {
        $store = $this->storeRepository->createOrUpdate($request->input());

        event(new CreatedContentEvent(STORE_MODULE_SCREEN_NAME, $request, $store));

        return $response
            ->setPreviousUrl(route('marketplace.store.index'))
            ->setNextUrl(route('marketplace.store.edit', $store->id))
            ->setMessage(trans('core/base::notices.create_success_message'));
    }

    public function edit(int|string $id, FormBuilder $formBuilder, Request $request)
    {
        $store = $this->storeRepository->findOrFail($id);

        event(new BeforeEditContentEvent($request, $store));

        page_title()->setTitle(trans('plugins/marketplace::store.edit') . ' "' . $store->name . '"');

        return $formBuilder->create(StoreForm::class, ['model' => $store])->renderForm();
    }

    public function update(int|string $id, StoreRequest $request, BaseHttpResponse $response)
    {
        $store = $this->storeRepository->findOrFail($id);

        $store->fill($request->input());

        $this->storeRepository->createOrUpdate($store);

        $customer = $store->customer;
        if ($customer && $customer->id) {
            $vendorInfo = $customer->vendorInfo;
            $vendorInfo->payout_payment_method = $request->input('payout_payment_method');
            $vendorInfo->bank_info = $request->input('bank_info', []);
            $vendorInfo->tax_info = $request->input('tax_info', []);
            $vendorInfo->save();
        }

        event(new UpdatedContentEvent(STORE_MODULE_SCREEN_NAME, $request, $store));

        return $response
            ->setPreviousUrl(route('marketplace.store.index'))
            ->setMessage(trans('core/base::notices.update_success_message'));
    }

    public function destroy(int|string $id, Request $request, BaseHttpResponse $response)
    {
        try {
            $store = $this->storeRepository->findOrFail($id);

            $this->storeRepository->delete($store);

            event(new DeletedContentEvent(STORE_MODULE_SCREEN_NAME, $request, $store));

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
            $store = $this->storeRepository->findOrFail($id);
            $this->storeRepository->delete($store);
            event(new DeletedContentEvent(STORE_MODULE_SCREEN_NAME, $request, $store));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }
}
