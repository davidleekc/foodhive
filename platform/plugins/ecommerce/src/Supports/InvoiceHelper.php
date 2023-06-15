<?php

namespace Botble\Ecommerce\Supports;

use ArPHP\I18N\Arabic;
use Barryvdh\DomPDF\Facade\Pdf;
use Barryvdh\DomPDF\PDF as PDFHelper;
use BaseHelper;
use Botble\Base\Supports\TwigCompiler;
use Botble\Ecommerce\Enums\InvoiceStatusEnum;
use EcommerceHelper as EcommerceHelperFacade;
use Botble\Ecommerce\Models\Invoice;
use Botble\Ecommerce\Models\InvoiceItem;
use Botble\Ecommerce\Models\Order;
use Botble\Ecommerce\Models\Product;
use Botble\Payment\Enums\PaymentMethodEnum;
use Botble\Payment\Enums\PaymentStatusEnum;
use Botble\Payment\Models\Payment;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\File;
use RvMedia;

class InvoiceHelper
{
    public function store(Order $order)
    {
        if ($order->invoice()->exists()) {
            return $order->invoice()->first();
        }

        $address = $order->shippingAddress;

        if (EcommerceHelperFacade::isBillingAddressEnabled() && $order->billingAddress->id) {
            $address = $order->billingAddress;
        }

        $invoiceData = [
            'reference_id' => $order->id,
            'reference_type' => Order::class,
            'customer_name' => $address->name ?: $order->user->name,
            'company_name' => '',
            'company_logo' => null,
            'customer_email' => $address->email ?: $order->user->email,
            'customer_phone' => $address->phone,
            'customer_address' => $address->full_address,
            'customer_tax_id' => null,
            'payment_id' => null,
            'status' => InvoiceStatusEnum::COMPLETED,
            'paid_at' => now(),
            'tax_amount' => $order->tax_amount,
            'shipping_amount' => $order->shipping_amount,
            'discount_amount' => $order->discount_amount,
            'sub_total' => $order->sub_total,
            'amount' => $order->amount,
            'shipping_method' => $order->shipping_method,
            'shipping_option' => $order->shipping_option,
            'coupon_code' => $order->coupon_code,
            'discount_description' => $order->discount_description,
            'description' => $order->description,
        ];

        if (is_plugin_active('payment')) {
            $invoiceData = array_merge($invoiceData, [
                'payment_id' => $order->payment->id,
                'status' => $order->payment->status,
                'paid_at' => $order->payment->status == PaymentStatusEnum::COMPLETED ? Carbon::now() : null,
            ]);
        }

        $invoice = new Invoice($invoiceData);

        $invoice->save();

        foreach ($order->products as $orderProduct) {
            $invoice->items()->create([
                'reference_id' => $orderProduct->product_id,
                'reference_type' => Product::class,
                'name' => $orderProduct->product_name,
                'description' => null,
                'image' => $orderProduct->product_image,
                'qty' => $orderProduct->qty,
                'sub_total' => $orderProduct->price,
                'tax_amount' => $orderProduct->tax_amount,
                'discount_amount' => 0,
                'amount' => $orderProduct->price * $orderProduct->qty + $orderProduct->tax_amount,
                'options' => json_encode($orderProduct->options),
            ]);
        }

        do_action(INVOICE_PAYMENT_CREATED, $invoice);

        return $invoice;
    }

    public function makeInvoicePDF(Invoice $invoice): PDFHelper
    {
        $fontsPath = storage_path('fonts');

        if (! File::isDirectory($fontsPath)) {
            File::makeDirectory($fontsPath);
        }

        $content = $this->getInvoiceTemplate();

        if ($content) {
            $twigCompiler = (new TwigCompiler())->addExtension(new TwigExtension());
            $content = $twigCompiler->compile($content, $this->getDataForInvoiceTemplate($invoice));

            if (setting('job_board_invoice_support_arabic_language', 0) == 1) {
                $arabic = new Arabic();
                $p = $arabic->arIdentify($content);

                for ($i = count($p) - 1; $i >= 0; $i -= 2) {
                    $utf8ar = $arabic->utf8Glyphs(substr($content, $p[$i - 1], $p[$i] - $p[$i - 1]));
                    $content = substr_replace($content, $utf8ar, $p[$i - 1], $p[$i] - $p[$i - 1]);
                }
            }
        }

        $pdf = Pdf::loadHTML($content, 'UTF-8');

        return $pdf
            ->setPaper('a4')
            ->setWarnings(false)
            ->setOption('tempDir', storage_path('app'))
            ->setOption('logOutputFile', storage_path('logs/pdf.log'))
            ->setOption('isRemoteEnabled', true);
    }

    public function generateInvoice(Invoice $invoice): string
    {
        $folderPath = storage_path('app/public');
        if (! File::isDirectory($folderPath)) {
            File::makeDirectory($folderPath);
        }

        $invoice = $folderPath . '/invoice-' . $invoice->code . '.pdf';

        if (File::exists($invoice)) {
            return $invoice;
        }

        $this->makeInvoicePDF($invoice)->save($invoice);

        return $invoice;
    }

    public function downloadInvoice(Invoice $invoice): Response
    {
        return $this->makeInvoicePDF($invoice)->download('invoice-' . $invoice->code . '.pdf');
    }

    public function streamInvoice(Invoice $invoice): Response
    {
        return $this->makeInvoicePDF($invoice)->stream();
    }

    public function getInvoiceTemplate(): string
    {
        $defaultPath = platform_path('plugins/ecommerce/resources/templates/invoice.tpl');
        $storagePath = storage_path('app/templates/invoice.tpl');

        if ($storagePath && File::exists($storagePath)) {
            $templateHtml = BaseHelper::getFileData($storagePath, false);
        } else {
            $templateHtml = File::exists($defaultPath) ? BaseHelper::getFileData($defaultPath, false) : '';
        }

        return (string)$templateHtml;
    }

    protected function getDataForInvoiceTemplate(Invoice $invoice): array
    {
        $logo = get_ecommerce_setting('company_logo_for_invoicing') ?: (theme_option(
            'logo_in_invoices'
        ) ?: theme_option('logo'));

        return [
            'invoice' => $invoice->loadMissing('items')->toArray(),
            'logo' => $logo,
            'logo_full_path' => RvMedia::getRealPath($logo),
            'site_title' => theme_option('site_title'),
            'company_logo_full_path' => RvMedia::getRealPath($logo),
            'payment_method' => $invoice->payment->payment_channel->label(),
            'payment_status' => $invoice->payment->status->label(),
            'payment_description' => ($invoice->payment->payment_channel == PaymentMethodEnum::BANK_TRANSFER && $invoice->payment->status == PaymentStatusEnum::PENDING)
                ? BaseHelper::clean(get_payment_setting('description', $invoice->payment->payment_channel))
                : null,
            'is_tax_enabled' => EcommerceHelperFacade::isTaxEnabled(),
            'settings' => [
                'using_custom_font_for_invoice' => (bool) get_ecommerce_setting('using_custom_font_for_invoice'),
                'custom_font_family' => get_ecommerce_setting('invoice_font_family'),
                'font_family' => get_ecommerce_setting('invoice_font_family', 0) == 1
                    ? get_ecommerce_setting('invoice_font_family', 'DejaVu Sans')
                    : 'DejaVu Sans',
                'enable_invoice_stamp' => get_ecommerce_setting('enable_invoice_stamp'),
            ],
            'invoice_header_filter' => apply_filters('ecommerce_invoice_header', null, $invoice),
            'invoice_body_filter' => apply_filters('ecommerce_invoice_body', null, $invoice),
            'ecommerce_invoice_footer' => apply_filters('ecommerce_invoice_footer', null, $invoice),
        ];
    }

    public function getDataForPreview(): Invoice
    {
        $invoice = new Invoice([
            'code' => 'INV-1',
            'customer_name' => 'Odie Miller',
            'store_name' => 'LinkedIn',
            'store_address' => '701 Norman Street Los Angeles California 90008',
            'customer_email' => 'contact@example.com',
            'customer_phone' => '+0123456789',
            'customer_address' => ' 14059 Triston Crossroad South Lillie, NH 84777-1634',
            'status' => InvoiceStatusEnum::PENDING,
            'created_at' => '2022-12-23 08:14:09',
        ]);

        $items = [];

        foreach (range(1, 3) as $i) {
            $amount = rand(10, 1000);
            $qty = rand(1, 10);

            $items[] = new InvoiceItem([
                'name' => "Item $i",
                'description' => "Description of item $i",
                'sub_total' => $amount * $qty,
                'amount' => $amount,
                'qty' => $qty,
            ]);

            $invoice->amount += $amount * $qty;
            $invoice->sub_total = $invoice->amount;
        }

        $payment = new Payment([
            'payment_channel' => PaymentMethodEnum::BANK_TRANSFER,
            'status' => PaymentStatusEnum::PENDING,
        ]);

        $invoice->setRelation('payment', $payment);
        $invoice->setRelation('items', collect($items));

        return $invoice;
    }

    public function getVariables(): array
    {
        return [
            'invoice.*' => __('Invoice information from database, ex: invoice.code, invoice.amount, ...'),
            'logo_full_path' => __('The site logo with full url'),
            'company_logo_full_path' => __('The company logo of invoice with full url'),
            'payment_method' => __('Payment method'),
            'payment_status' => __('Payment status'),
            'payment_description' => __('Payment description'),
            'get_ecommerce_setting(\'key\')' => __('Get the ecommerce setting from database'),
        ];
    }
}
