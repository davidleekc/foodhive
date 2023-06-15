<?php

namespace Botble\Ecommerce\Http\Controllers;

use Botble\Base\Events\BeforeEditContentEvent;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Forms\FormBuilder;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Ecommerce\Forms\ProductLabelForm;
use Botble\Ecommerce\Http\Requests\ProductLabelRequest;
use Botble\Ecommerce\Repositories\Interfaces\ProductLabelInterface;
use Botble\Ecommerce\Tables\ProductLabelTable;
use Exception;
use Illuminate\Http\Request;

class ProductLabelController extends BaseController
{
    public function __construct(protected ProductLabelInterface $productLabelRepository)
    {
    }

    public function index(ProductLabelTable $table)
    {
        page_title()->setTitle(trans('plugins/ecommerce::product-label.name'));

        return $table->renderTable();
    }

    public function create(FormBuilder $formBuilder)
    {
        page_title()->setTitle(trans('plugins/ecommerce::product-label.create'));

        return $formBuilder->create(ProductLabelForm::class)->renderForm();
    }

    public function store(ProductLabelRequest $request, BaseHttpResponse $response)
    {
        $productLabel = $this->productLabelRepository->createOrUpdate($request->input());

        event(new CreatedContentEvent(PRODUCT_LABEL_MODULE_SCREEN_NAME, $request, $productLabel));

        return $response
            ->setPreviousUrl(route('product-label.index'))
            ->setNextUrl(route('product-label.edit', $productLabel->id))
            ->setMessage(trans('core/base::notices.create_success_message'));
    }

    public function edit(int|string $id, FormBuilder $formBuilder, Request $request)
    {
        $productLabel = $this->productLabelRepository->findOrFail($id);

        event(new BeforeEditContentEvent($request, $productLabel));

        page_title()->setTitle(trans('plugins/ecommerce::product-label.edit') . ' "' . $productLabel->name . '"');

        return $formBuilder->create(ProductLabelForm::class, ['model' => $productLabel])->renderForm();
    }

    public function update(int|string $id, ProductLabelRequest $request, BaseHttpResponse $response)
    {
        $productLabel = $this->productLabelRepository->findOrFail($id);

        $productLabel->fill($request->input());

        $this->productLabelRepository->createOrUpdate($productLabel);

        event(new UpdatedContentEvent(PRODUCT_LABEL_MODULE_SCREEN_NAME, $request, $productLabel));

        return $response
            ->setPreviousUrl(route('product-label.index'))
            ->setMessage(trans('core/base::notices.update_success_message'));
    }

    public function destroy(int|string $id, Request $request, BaseHttpResponse $response)
    {
        try {
            $productLabel = $this->productLabelRepository->findOrFail($id);

            $this->productLabelRepository->delete($productLabel);

            event(new DeletedContentEvent(PRODUCT_LABEL_MODULE_SCREEN_NAME, $request, $productLabel));

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
            $productLabel = $this->productLabelRepository->findOrFail($id);
            $this->productLabelRepository->delete($productLabel);
            event(new DeletedContentEvent(PRODUCT_LABEL_MODULE_SCREEN_NAME, $request, $productLabel));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }
}
