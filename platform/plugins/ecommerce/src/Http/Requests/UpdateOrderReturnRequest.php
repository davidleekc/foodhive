<?php

namespace Botble\Ecommerce\Http\Requests;

use Botble\Ecommerce\Enums\OrderReturnStatusEnum;
use Botble\Support\Http\Requests\Request;

class UpdateOrderReturnRequest extends Request
{
    public function rules(): array
    {
        return [
            'return_status' => 'required|string|in:' . implode(',', OrderReturnStatusEnum::values()),
        ];
    }
}
