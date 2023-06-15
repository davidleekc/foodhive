<?php

namespace Botble\Faq\Http\Requests;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class FaqCategoryRequest extends Request
{
    public function rules(): array
    {
        return [
            'name' => 'required',
            'order' => 'required|integer|min:0|max:127',
            'status' => Rule::in(BaseStatusEnum::values()),
        ];
    }
}
