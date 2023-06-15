<?php

namespace Botble\Ecommerce\Http\Controllers;

use Assets;
use Botble\Base\Events\BeforeEditContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Ecommerce\Repositories\Interfaces\InvoiceInterface;
use Botble\Ecommerce\Tables\InvoiceTable;
use Exception;
use Illuminate\Http\Request;
use InvoiceHelper;

class InvoiceController extends BaseController
{
    public function __construct(protected InvoiceInterface $invoiceRepository)
    {
    }

    public function index(InvoiceTable $table)
    {
        page_title()->setTitle(trans('plugins/ecommerce::invoice.name'));

        return $table->renderTable();
    }

    public function edit(string $id, Request $request)
    {
        $invoice = $this->invoiceRepository->findOrFail($id);

        event(new BeforeEditContentEvent($request, $invoice));

        page_title()->setTitle(trans('plugins/ecommerce::invoice.edit') . ' "' . $invoice->code . '"');

        Assets::addStylesDirectly('vendor/core/plugins/ecommerce/css/invoice.css');

        return view('plugins/ecommerce::invoices.edit', compact('invoice'));
    }

    public function destroy(int|string $id, Request $request, BaseHttpResponse $response)
    {
        try {
            $invoice = $this->invoiceRepository->findOrFail($id);

            $this->invoiceRepository->delete($invoice);

            event(new DeletedContentEvent(INVOICE_MODULE_SCREEN_NAME, $request, $invoice));

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
            $invoice = $this->invoiceRepository->findOrFail($id);
            $this->invoiceRepository->delete($invoice);
            event(new DeletedContentEvent(INVOICE_MODULE_SCREEN_NAME, $request, $invoice));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }

    public function getGenerateInvoice(string $invoiceId, Request $request)
    {
        $invoice = $this->invoiceRepository->findOrFail($invoiceId);

        if ($request->input('type') === 'print') {
            return InvoiceHelper::streamInvoice($invoice);
        }

        return InvoiceHelper::downloadInvoice($invoice);
    }
}
