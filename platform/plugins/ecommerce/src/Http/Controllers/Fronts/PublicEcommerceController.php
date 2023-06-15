<?php

namespace Botble\Ecommerce\Http\Controllers\Fronts;

use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Ecommerce\Repositories\Interfaces\CurrencyInterface;
use Illuminate\Http\Request;

class PublicEcommerceController
{
    public function __construct(protected CurrencyInterface $currencyRepository)
    {
    }

    public function changeCurrency(Request $request, BaseHttpResponse $response, ?string $title = null)
    {
        if (empty($title)) {
            $title = $request->input('currency');
        }

        if (! $title) {
            return $response;
        }

        $currency = $this->currencyRepository->getFirstBy(['title' => $title]);

        if ($currency) {
            cms_currency()->setApplicationCurrency($currency);
        }

        return $response;
    }
}
