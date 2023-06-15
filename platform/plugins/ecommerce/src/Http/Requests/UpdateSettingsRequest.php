<?php

namespace Botble\Ecommerce\Http\Requests;

use BaseHelper;
use Botble\Support\Http\Requests\Request;

class UpdateSettingsRequest extends Request
{
    public function rules(): array
    {
        return [
            'store_name' => 'required',
            'store_address' => 'required',
            'store_phone' => 'required|' . BaseHelper::getPhoneValidationRule(),
            'store_state' => 'nullable',
            'store_city' => 'nullable',
        ];
    }
}
