<?php

namespace Botble\SimpleSlider\Http\Requests;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class SimpleSliderRequest extends Request
{
    public function rules(): array
    {
        return [
            'name' => 'required',
            'key' => 'required',
            'description' => 'max:1000',
            'status' => Rule::in(BaseStatusEnum::values()),
        ];
    }
}
