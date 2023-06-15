<?php

namespace Botble\Ecommerce\Http\Requests;

use Botble\Support\Http\Requests\Request;

class ApplyCouponRequest extends Request
{
    public function rules(): array
    {
        return [
            'coupon_code' => 'required|max:255',
        ];
    }
}
