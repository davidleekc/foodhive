<?php

namespace Botble\Location\Http\Requests;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class StateRequest extends Request
{
    public function rules(): array
    {
        return [
            'name' => 'required',
            'country_id' => 'required',
            'order' => 'required|integer|min:0|max:127',
            'abbreviation' => 'max:3',
            'status' => Rule::in(BaseStatusEnum::values()),
        ];
    }
}
