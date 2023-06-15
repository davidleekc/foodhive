<?php

namespace Botble\Faq\Http\Requests;

use Botble\Support\Http\Requests\Request;

class FaqRequest extends Request
{
    public function rules(): array
    {
        return [
            'category_id' => 'required',
            'question' => 'required',
            'answer' => 'required',
        ];
    }
}
