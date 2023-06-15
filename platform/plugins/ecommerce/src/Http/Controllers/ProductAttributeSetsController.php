<?php

namespace Botble\Ecommerce\Http\Controllers;

use Assets;
use Botble\Base\Forms\FormBuilder;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Ecommerce\Forms\ProductAttributeSetForm;
use Botble\Ecommerce\Http\Requests\ProductAttributeSetsRequest;
use Botble\Ecommerce\Repositories\Interfaces\ProductAttributeSetInterface;
use Botble\Ecommerce\Repositories\Interfaces\ProductCategoryInterface;
use Botble\Ecommerce\Services\ProductAttributes\StoreAttributeSetService;
use Botble\Ecommerce\Tables\ProductAttributeSetsTable;
use Exception;
use Illuminate\Http\Request;

class ProductAttributeSetsController extends BaseController
{
    public function __construct(
        protected ProductAttributeSetInterface $productAttributeSetRepository,
        protected ProductCategoryInterface $productCategoryRepository
    ) {
    }

    public function index(ProductAttributeSetsTable $dataTable)
    {
        page_title()->setTitle(trans('plugins/ecommerce::product-attributes.name'));

        return $dataTable->renderTable();
    }

    public function create(FormBuilder $formBuilder)
    {
        page_title()->setTitle(trans('plugins/ecommerce::product-attributes.create'));

        Assets::addScripts(['spectrum', 'jquery-ui'])
            ->addStyles(['spectrum'])
            ->addStylesDirectly([
                asset('vendor/core/plugins/ecommerce/css/ecommerce-product-attributes.css'),
            ])
            ->addScriptsDirectly([
                asset('vendor/core/plugins/ecommerce/js/ecommerce-product-attributes.js'),
            ]);

        return $formBuilder->create(ProductAttributeSetForm::class)->renderForm();
    }

    public function store(
        ProductAttributeSetsRequest $request,
        StoreAttributeSetService $service,
        BaseHttpResponse $response
    ) {
        $productAttributeSet = $this->productAttributeSetRepository->getModel();

        $productAttributeSet = $service->execute($request, $productAttributeSet);

        return $response
            ->setPreviousUrl(route('product-attribute-sets.index'))
            ->setNextUrl(route('product-attribute-sets.edit', $productAttributeSet->id))
            ->setMessage(trans('core/base::notices.create_success_message'));
    }

    public function edit(int|string $id, FormBuilder $formBuilder)
    {
        page_title()->setTitle(trans('plugins/ecommerce::product-attributes.edit'));

        $productAttributeSet = $this->productAttributeSetRepository->findOrFail($id);

        Assets::addScripts(['spectrum', 'jquery-ui'])
            ->addStyles(['spectrum'])
            ->addStylesDirectly([
                'vendor/core/plugins/ecommerce/css/ecommerce-product-attributes.css',
            ])
            ->addScriptsDirectly([
                'vendor/core/plugins/ecommerce/js/ecommerce-product-attributes.js',
            ]);

        return $formBuilder
            ->create(ProductAttributeSetForm::class, ['model' => $productAttributeSet])
            ->renderForm();
    }

    public function update(
        int|string $id,
        ProductAttributeSetsRequest $request,
        StoreAttributeSetService $service,
        BaseHttpResponse $response
    ) {
        $productAttributeSet = $this->productAttributeSetRepository->findOrFail($id);

        $service->execute($request, $productAttributeSet);

        return $response
            ->setPreviousUrl(route('product-attribute-sets.index'))
            ->setMessage(trans('core/base::notices.update_success_message'));
    }

    public function destroy(int|string $id, BaseHttpResponse $response)
    {
        try {
            $productAttributeSet = $this->productAttributeSetRepository->findOrFail($id);
            $this->productAttributeSetRepository->delete($productAttributeSet);

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
            $productAttributeSet = $this->productAttributeSetRepository->findOrFail($id);
            $this->productAttributeSetRepository->delete($productAttributeSet);
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }
}
