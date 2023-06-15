<?php

namespace Botble\Ecommerce\Http\Controllers;

use Botble\Base\Events\BeforeEditContentEvent;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Forms\FormBuilder;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Ecommerce\Forms\ProductCollectionForm;
use Botble\Ecommerce\Http\Requests\ProductCollectionRequest;
use Botble\Ecommerce\Repositories\Interfaces\ProductCollectionInterface;
use Botble\Ecommerce\Tables\ProductCollectionTable;
use Exception;
use Illuminate\Http\Request;

class ProductCollectionController extends BaseController
{
    public function __construct(protected ProductCollectionInterface $productCollectionRepository)
    {
    }

    public function index(ProductCollectionTable $dataTable)
    {
        page_title()->setTitle(trans('plugins/ecommerce::product-collections.name'));

        return $dataTable->renderTable();
    }

    public function create(FormBuilder $formBuilder)
    {
        page_title()->setTitle(trans('plugins/ecommerce::product-collections.create'));

        return $formBuilder->create(ProductCollectionForm::class)->renderForm();
    }

    public function store(ProductCollectionRequest $request, BaseHttpResponse $response)
    {
        $productCollection = $this->productCollectionRepository->getModel();
        $productCollection->fill($request->input());

        $productCollection->slug = $this->productCollectionRepository->createSlug($request->input('slug'), 0);

        $productCollection = $this->productCollectionRepository->createOrUpdate($productCollection);

        event(new CreatedContentEvent(PRODUCT_COLLECTION_MODULE_SCREEN_NAME, $request, $productCollection));

        return $response
            ->setPreviousUrl(route('product-collections.index'))
            ->setNextUrl(route('product-collections.edit', $productCollection->id))
            ->setMessage(trans('core/base::notices.create_success_message'));
    }

    public function edit(int|string $id, FormBuilder $formBuilder, Request $request)
    {
        $productCollection = $this->productCollectionRepository->findOrFail($id);

        event(new BeforeEditContentEvent($request, $productCollection));

        page_title()->setTitle(trans('plugins/ecommerce::product-collections.edit') . ' "' . $productCollection->name . '"');

        return $formBuilder
            ->create(ProductCollectionForm::class, ['model' => $productCollection])
            ->remove('slug')
            ->renderForm();
    }

    public function update(int|string $id, ProductCollectionRequest $request, BaseHttpResponse $response)
    {
        $productCollection = $this->productCollectionRepository->findOrFail($id);
        $productCollection->fill($request->input());

        $productCollection = $this->productCollectionRepository->createOrUpdate($productCollection);

        event(new UpdatedContentEvent(PRODUCT_COLLECTION_MODULE_SCREEN_NAME, $request, $productCollection));

        return $response
            ->setPreviousUrl(route('product-collections.index'))
            ->setMessage(trans('core/base::notices.update_success_message'));
    }

    public function destroy(int|string $id, BaseHttpResponse $response, Request $request)
    {
        $productCollection = $this->productCollectionRepository->findOrFail($id);

        try {
            $this->productCollectionRepository->delete($productCollection);

            event(new DeletedContentEvent(PRODUCT_COLLECTION_MODULE_SCREEN_NAME, $request, $productCollection));

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
            $productCollection = $this->productCollectionRepository->findOrFail($id);
            $this->productCollectionRepository->delete($productCollection);
            event(new DeletedContentEvent(PRODUCT_COLLECTION_MODULE_SCREEN_NAME, $request, $productCollection));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }

    public function getListForSelect(BaseHttpResponse $response)
    {
        $productCollections = $this->productCollectionRepository
            ->getModel()
            ->select(['id', 'name'])
            ->get()
            ->toArray();

        return $response->setData($productCollections);
    }
}
