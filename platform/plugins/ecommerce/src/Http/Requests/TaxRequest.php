<?php

namespace Botble\Ecommerce\Http\Requests;

use Botble\Support\Http\Requests\Request;

class TaxRequest extends Request
{
    public function rules(): array
    {
        return [
            'title' => 'required|max:255',
            'percentage' => 'required|between:0,99.99',
            'priority' => 'required|min:0',
        ];
    }
}
