<?php

namespace Botble\Marketplace\Http\Requests;

use BaseHelper;
use Botble\Marketplace\Enums\PayoutPaymentMethodsEnum;
use Botble\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class SettingRequest extends Request
{
    public function rules(): array
    {
        return [
            'name' => 'required|max:120|min:2',
            'email' => 'required|max:60|min:6|email|unique:ec_customers,email,' . $this->user('customer')->id,
            'phone' => 'required|' . BaseHelper::getPhoneValidationRule(),
            'slug' => 'max:255',
            'bank_info.name' => 'max:120',
            'bank_info.number' => 'max:60',
            'bank_info.full_name' => 'max:120',
            'bank_info.description' => 'max:500',
            'payout_payment_method' => Rule::in(PayoutPaymentMethodsEnum::values()),
        ];
    }
}
