<?php

namespace Botble\Ecommerce\Http\Requests;

use BaseHelper;
use Botble\Support\Http\Requests\Request;

class CreateOrderRequest extends Request
{
    public function rules(): array
    {
        return [
            'customer_id' => 'required|exists:ec_customers,id',
            'customer_address.phone' => BaseHelper::getPhoneValidationRule(),
        ];
    }

    public function attributes(): array
    {
        return [
            'customer_id' => trans('plugins/ecommerce::order.customer_label'),
        ];
    }
}
