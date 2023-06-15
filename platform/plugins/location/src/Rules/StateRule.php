<?php

namespace Botble\Location\Rules;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Location\Repositories\Interfaces\StateInterface;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Arr;

class StateRule implements DataAwareRule, Rule
{
    protected array $data = [];

    protected ?string $countryKey;

    public function __construct(?string $countryKey = '')
    {
        $this->countryKey = $countryKey;
    }

    public function setData($data): self
    {
        $this->data = $data;

        return $this;
    }

    public function passes($attribute, $value): bool
    {
        $condition = [
            'id' => $value,
            'status' => BaseStatusEnum::PUBLISHED,
        ];

        if ($this->countryKey) {
            $countryId = Arr::get($this->data, $this->countryKey);
            if (! $countryId) {
                return false;
            }
            $condition['country_id'] = $countryId;
        }

        return app(StateInterface::class)->count($condition);
    }

    public function message(): string
    {
        return trans('validation.exists');
    }
}
