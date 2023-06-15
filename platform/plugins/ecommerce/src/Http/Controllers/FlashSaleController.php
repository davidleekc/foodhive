<?php

namespace Botble\Ecommerce\Http\Controllers;

use Botble\Base\Events\BeforeEditContentEvent;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Forms\FormBuilder;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Ecommerce\Forms\FlashSaleForm;
use Botble\Ecommerce\Http\Requests\FlashSaleRequest;
use Botble\Ecommerce\Models\FlashSale;
use Botble\Ecommerce\Repositories\Interfaces\FlashSaleInterface;
use Botble\Ecommerce\Tables\FlashSaleTable;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class FlashSaleController extends BaseController
{
    public function __construct(protected FlashSaleInterface $flashSaleRepository)
    {
    }

    public function index(FlashSaleTable $table)
    {
        page_title()->setTitle(trans('plugins/ecommerce::flash-sale.name'));

        return $table->renderTable();
    }

    public function create(FormBuilder $formBuilder)
    {
        page_title()->setTitle(trans('plugins/ecommerce::flash-sale.create'));

        return $formBuilder->create(FlashSaleForm::class)->renderForm();
    }

    public function store(FlashSaleRequest $request, BaseHttpResponse $response)
    {
        $flashSale = $this->flashSaleRepository->createOrUpdate($request->input());

        event(new CreatedContentEvent(FLASH_SALE_MODULE_SCREEN_NAME, $request, $flashSale));

        $this->storeProducts($request, $flashSale);

        return $response
            ->setPreviousUrl(route('flash-sale.index'))
            ->setNextUrl(route('flash-sale.edit', $flashSale->id))
            ->setMessage(trans('core/base::notices.create_success_message'));
    }

    protected function storeProducts(FlashSaleRequest $request, FlashSale $flashSale)
    {
        $products = array_filter(explode(',', $request->input('products')));

        $flashSale->products()->detach();

        foreach ($products as $index => $productId) {
            if (! (int)$productId) {
                continue;
            }

            $extra = Arr::get($request->input('products_extra', []), $index);

            if (! $extra || ! isset($extra['price']) || ! isset($extra['quantity'])) {
                continue;
            }

            $extra['price'] = (float)$extra['price'];
            $extra['quantity'] = (int)$extra['quantity'];

            if ($flashSale->products()->where('id', $productId)->count()) {
                $flashSale->products()->sync([(int)$productId => $extra]);
            } else {
                $flashSale->products()->attach($productId, $extra);
            }
        }

        return count($products);
    }

    public function edit(int|string $id, FormBuilder $formBuilder, Request $request)
    {
        $flashSale = $this->flashSaleRepository->findOrFail($id);

        event(new BeforeEditContentEvent($request, $flashSale));

        page_title()->setTitle(trans('plugins/ecommerce::flash-sale.edit') . ' "' . $flashSale->name . '"');

        return $formBuilder->create(FlashSaleForm::class, ['model' => $flashSale])->renderForm();
    }

    public function update(int|string $id, FlashSaleRequest $request, BaseHttpResponse $response)
    {
        $flashSale = $this->flashSaleRepository->findOrFail($id);

        $flashSale->fill($request->input());

        $this->flashSaleRepository->createOrUpdate($flashSale);

        $this->storeProducts($request, $flashSale);

        event(new UpdatedContentEvent(FLASH_SALE_MODULE_SCREEN_NAME, $request, $flashSale));

        return $response
            ->setPreviousUrl(route('flash-sale.index'))
            ->setMessage(trans('core/base::notices.update_success_message'));
    }

    public function destroy(int|string $id, Request $request, BaseHttpResponse $response)
    {
        try {
            $flashSale = $this->flashSaleRepository->findOrFail($id);

            $this->flashSaleRepository->delete($flashSale);

            event(new DeletedContentEvent(FLASH_SALE_MODULE_SCREEN_NAME, $request, $flashSale));

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
            $flashSale = $this->flashSaleRepository->findOrFail($id);
            $this->flashSaleRepository->delete($flashSale);
            event(new DeletedContentEvent(FLASH_SALE_MODULE_SCREEN_NAME, $request, $flashSale));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }
}
