<?php

namespace Botble\Ecommerce\Services;

use Botble\Base\Models\BaseModel;
use Botble\Ecommerce\Enums\ShippingMethodEnum;
use Botble\Ecommerce\Enums\ShippingRuleTypeEnum;
use Botble\Ecommerce\Models\Shipping;
use Botble\Ecommerce\Repositories\Interfaces\AddressInterface;
use Botble\Ecommerce\Repositories\Interfaces\ProductInterface;
use Botble\Ecommerce\Repositories\Interfaces\ShippingInterface;
use Botble\Ecommerce\Repositories\Interfaces\ShippingRuleInterface;
use Botble\Ecommerce\Repositories\Interfaces\StoreLocatorInterface;
use Botble\Support\Services\Cache\Cache;
use EcommerceHelper;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;

class HandleShippingFeeService
{
    protected array $shipping;

    protected ?BaseModel $shippingDefault = null;

    protected array $shippingRules;

    protected Cache $cache;

    protected bool $useCache;

    public function __construct(
        protected ShippingInterface $shippingRepository,
        protected AddressInterface $addressRepository,
        protected ShippingRuleInterface $shippingRuleRepository,
        protected ProductInterface $productRepository,
        protected StoreLocatorInterface $storeLocatorRepository
    ) {
        $this->shipping = [];
        $this->shippingRules = [];

        $this->cache = new Cache(app('cache'), self::class);
        $this->useCache = true;
    }

    public function execute(array $data, ?string $method = null, ?string $option = null): array
    {
        $result = [];

        $cacheKey = $this->getCacheKey($data);
        $cacheValue = $this->getCacheValue($cacheKey);
        if ($cacheValue) {
            $result[ShippingMethodEnum::DEFAULT] = $cacheValue;
        } else {
            $default = $this->getShippingFee($data, ShippingMethodEnum::DEFAULT, $option);

            if ($default) {
                $result[ShippingMethodEnum::DEFAULT] = $default;
            }

            $this->setCacheValue($cacheKey, $default);
        }

        $result = apply_filters('handle_shipping_fee', $result, $data, $option);

        if ($method) {
            $options = Arr::get($result, $method, []);

            if (! is_array($options) || ! count($options)) {
                return [];
            }

            $filtered = Arr::where($options, function ($rate) {
                return ! Arr::get($rate, 'disabled');
            });

            $response = Arr::get($filtered, $option);

            return $response ? [$response] : [];
        }

        if (get_ecommerce_setting('hide_other_shipping_options_if_it_has_free_shipping', false)) {
            $hasFreeShipping = false;

            foreach ($result as $item) {
                foreach ($item as $option) {
                    if ((float)$option['price'] == 0) {
                        $hasFreeShipping = true;

                        break;
                    }
                }
            }

            if ($hasFreeShipping) {
                foreach ($result as $itemKey => $item) {
                    foreach ($item as $optionKey => $option) {
                        if ((float)$option['price'] > 0) {
                            Arr::forget($result, $itemKey . '.' . $optionKey);
                        }
                    }
                }
            }
        }

        return $result;
    }

    protected function getShippingFee(array $data, string $method, ?string $option = null): array
    {
        $weight = EcommerceHelper::validateOrderWeight(Arr::get($data, 'weight'));

        $orderTotal = Arr::get($data, 'order_total', 0);

        if (EcommerceHelper::isUsingInMultipleCountries()) {
            $country = Arr::get($data, 'country');
        } else {
            $country = EcommerceHelper::getFirstCountryId();
        }

        $result = [];
        if ($method == ShippingMethodEnum::DEFAULT) {
            $methodKey = $method . '-' . $country;
            if (Arr::has($this->shipping, $methodKey)) {
                $shipping = Arr::get($this->shipping, $methodKey);
            } else {
                $shipping = $this->shippingRepository->getFirstBy(['country' => $country]);
                Arr::set($this->shipping, $methodKey, $shipping);
            }

            if (! empty($shipping)) {
                $result = $this->calculateDefaultFeeByAddress(
                    $shipping,
                    $weight,
                    $orderTotal,
                    $data,
                    $option
                );
            }

            if (empty($result)) {
                if ($this->shippingDefault) {
                    $default = $this->shippingDefault;
                } else {
                    $default = $this->shippingRepository
                        ->getModel()
                        ->whereNull('country')
                        ->first();
                    $this->shippingDefault = $default;
                }

                $result = $this->calculateDefaultFeeByAddress(
                    $default,
                    $weight,
                    $orderTotal,
                    $data,
                    $option
                );
            }
        }

        if ($result) {
            $result = collect($result)->sortBy('price')->toArray();
        }

        return $result;
    }

    protected function calculateDefaultFeeByAddress(
        ?Shipping $shipping,
        int|float $weight,
        int|float $orderTotal,
        array $data,
        string $option = null
    ): array {
        $result = [];

        if ($shipping) {
            $ruleKey = 'rule-option-' . $option;
            if (Arr::has($this->shippingRules, $ruleKey)) {
                $rule = Arr::get($this->shippingRules, $ruleKey);
            } else {
                $rule = $this->shippingRuleRepository->findById($option, ['items']);
                Arr::set($this->shippingRules, $ruleKey, $rule);
            }
            $city = Arr::get($data, 'city');
            $state = Arr::get($data, 'state');

            if ($rule) {
                $ruleDetail = $rule
                    ->items
                    ->where('city', $city)
                    ->where('is_enabled', 1)
                    ->first();
                if ($ruleDetail) {
                    $result[$rule->id] = [
                        'name' => $rule->name,
                        'price' => $rule->price + $ruleDetail->adjustment_price,
                    ];
                } else {
                    $result[$rule->id] = [
                        'name' => $rule->name,
                        'price' => $rule->price,
                    ];
                }
            } else {
                $zipCode = Arr::get($data, 'address_to.zip_code');

                $rules = $this->shippingRuleRepository
                    ->getModel()
                    ->where(function (Builder $query) use ($orderTotal, $shipping) {
                        $query
                            ->where('shipping_id', $shipping->id)
                            ->where('type', ShippingRuleTypeEnum::BASED_ON_PRICE)
                            ->where('from', '<=', $orderTotal)
                            ->where(function (Builder $sub) use ($orderTotal) {
                                $sub
                                    ->whereNull('to')
                                    ->orWhere('to', '>=', $orderTotal);
                            });
                    })
                    ->orWhere(function (Builder $query) use ($weight, $shipping) {
                        $query
                            ->where('shipping_id', $shipping->id)
                            ->where('type', ShippingRuleTypeEnum::BASED_ON_WEIGHT)
                            ->where('from', '<=', $weight)
                            ->where(function (Builder $sub) use ($weight) {
                                $sub
                                    ->whereNull('to')
                                    ->orWhere('to', '>=', $weight);
                            });
                    });

                if (EcommerceHelper::loadCountriesStatesCitiesFromPluginLocation()) {
                    $rules = $rules
                        ->orWhere(function (Builder $query) use ($shipping) {
                            $query
                                ->where('shipping_id', $shipping->id)
                                ->where('type', ShippingRuleTypeEnum::BASED_ON_LOCATION);
                        });
                }

                if (EcommerceHelper::isZipCodeEnabled()) {
                    $rules = $rules
                        ->orWhere(function (Builder $query) use ($zipCode, $shipping) {
                            $query
                                ->where('shipping_id', $shipping->id)
                                ->where('type', ShippingRuleTypeEnum::BASED_ON_ZIPCODE)
                                ->whereHas('items', function (Builder $sub) use ($zipCode) {
                                    $sub->where(['zip_code' => $zipCode]);
                                });
                        });
                }

                $rules = $rules
                    ->with([
                        'items' => function ($query) {
                            $query
                                ->where(['is_enabled' => 1])
                                ->orderBy('adjustment_price');
                        },
                    ])
                    ->get();

                foreach ($rules as $rule) {
                    switch ($rule->type) {
                        case ShippingRuleTypeEnum::BASED_ON_ZIPCODE:
                            $ruleItem = $rule
                                ->items
                                ->where('zip_code', $zipCode)
                                ->first();

                            if (! $ruleItem) {
                                continue 2;
                            }

                            break;
                        case ShippingRuleTypeEnum::BASED_ON_LOCATION:
                            $ruleItem = $rule
                                ->items
                                ->where('state', $state)
                                ->where('city', $city)
                                ->first();

                            if (! $ruleItem) {
                                $ruleItem = $rule
                                    ->items
                                    ->where('state', $state)
                                    ->whereIn('city', ['', null, 0])
                                    ->first();
                            }

                            break;
                        default:
                            $ruleItem = $rule
                                ->items
                                ->where('state', $state)
                                ->where('city', $city)
                                ->first();

                            break;
                    }

                    if ($ruleItem) {
                        $result[$rule->id] = [
                            'name' => $rule->name,
                            'price' => max($rule->price + $ruleItem->adjustment_price, 0),
                        ];
                    } else {
                        $result[$rule->id] = [
                            'name' => $rule->name,
                            'price' => $rule->price,
                        ];
                    }
                }
            }
        }

        return $result;
    }

    protected function getCacheKey(array $data): string
    {
        return md5(json_encode(Arr::only($data, ['origin', 'address_to', 'items', 'extra'])));
    }

    protected function getCacheValue(string $key): mixed
    {
        if ($this->useCache) {
            return $this->cache->get($key);
        }

        return null;
    }

    protected function setCacheValue(string $key, mixed $value): bool
    {
        if ($key) {
            return $this->cache->put($key, $value);
        }

        return true;
    }
}
