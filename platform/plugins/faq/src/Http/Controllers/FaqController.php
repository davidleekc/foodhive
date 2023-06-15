<?php

namespace Botble\Faq\Http\Controllers;

use Botble\Base\Events\BeforeEditContentEvent;
use Botble\Base\Traits\HasDeleteManyItemsTrait;
use Botble\Faq\Http\Requests\FaqRequest;
use Botble\Faq\Repositories\Interfaces\FaqInterface;
use Botble\Base\Http\Controllers\BaseController;
use Exception;
use Illuminate\Http\Request;
use Botble\Faq\Tables\FaqTable;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Faq\Forms\FaqForm;
use Botble\Base\Forms\FormBuilder;

class FaqController extends BaseController
{
    use HasDeleteManyItemsTrait;

    public function __construct(protected FaqInterface $faqRepository)
    {
    }

    public function index(FaqTable $table)
    {
        page_title()->setTitle(trans('plugins/faq::faq.name'));

        return $table->renderTable();
    }

    public function create(FormBuilder $formBuilder)
    {
        page_title()->setTitle(trans('plugins/faq::faq.create'));

        return $formBuilder->create(FaqForm::class)->renderForm();
    }

    public function store(FaqRequest $request, BaseHttpResponse $response)
    {
        $faq = $this->faqRepository->createOrUpdate($request->input());

        event(new CreatedContentEvent(FAQ_MODULE_SCREEN_NAME, $request, $faq));

        return $response
            ->setPreviousUrl(route('faq.index'))
            ->setNextUrl(route('faq.edit', $faq->id))
            ->setMessage(trans('core/base::notices.create_success_message'));
    }

    public function edit(int|string $id, FormBuilder $formBuilder, Request $request)
    {
        $faq = $this->faqRepository->findOrFail($id);

        event(new BeforeEditContentEvent($request, $faq));

        page_title()->setTitle(trans('plugins/faq::faq.edit') . ' "' . $faq->question . '"');

        return $formBuilder->create(FaqForm::class, ['model' => $faq])->renderForm();
    }

    public function update(int|string $id, FaqRequest $request, BaseHttpResponse $response)
    {
        $faq = $this->faqRepository->findOrFail($id);

        $faq->fill($request->input());

        $this->faqRepository->createOrUpdate($faq);

        event(new UpdatedContentEvent(FAQ_MODULE_SCREEN_NAME, $request, $faq));

        return $response
            ->setPreviousUrl(route('faq.index'))
            ->setMessage(trans('core/base::notices.update_success_message'));
    }

    public function destroy(int|string $id, Request $request, BaseHttpResponse $response)
    {
        try {
            $faq = $this->faqRepository->findOrFail($id);

            $this->faqRepository->delete($faq);

            event(new DeletedContentEvent(FAQ_MODULE_SCREEN_NAME, $request, $faq));

            return $response->setMessage(trans('core/base::notices.delete_success_message'));
        } catch (Exception $exception) {
            return $response
                ->setError()
                ->setMessage($exception->getMessage());
        }
    }

    public function deletes(Request $request, BaseHttpResponse $response)
    {
        return $this->executeDeleteItems($request, $response, $this->faqRepository, FAQ_MODULE_SCREEN_NAME);
    }
}
