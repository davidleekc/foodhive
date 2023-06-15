<?php

namespace Botble\Ecommerce\Http\Requests;

use Botble\Ecommerce\Enums\ShippingMethodEnum;
use Botble\Payment\Enums\PaymentMethodEnum;
use Botble\Support\Http\Requests\Request;
use Cart;
use EcommerceHelper;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;

class CheckoutRequest extends Request
{
    public function rules(): array
    {
        $rules = [
            'amount' => 'required|min:0',
        ];

        if (is_plugin_active('payment')) {
            $paymentMethods = Arr::where(PaymentMethodEnum::values(), function ($value) {
                return get_payment_setting('status', $value) == 1;
            });

            $rules['payment_method'] = 'required|' . Rule::in($paymentMethods);
        }

        $addressId = $this->input('address.address_id');

        $products = Cart::instance('cart')->products();
        if (EcommerceHelper::isAvailableShipping($products)) {
            $rules['shipping_method'] = 'required|' . Rule::in(ShippingMethodEnum::values());
            if (auth('customer')->check()) {
                $rules['address.address_id'] = 'required_without:address.name';
                if (! $this->has('address.address_id') || $addressId === 'new') {
                    $rules = array_merge($rules, EcommerceHelper::getCustomerAddressValidationRules('address.'));
                }
            } else {
                $rules = array_merge($rules, EcommerceHelper::getCustomerAddressValidationRules('address.'));
            }
        }

        $billingAddressSameAsShippingAddress = false;
        if (EcommerceHelper::isBillingAddressEnabled()) {
            $isSaveOrderShippingAddress = EcommerceHelper::isSaveOrderShippingAddress($products);
            $rules['billing_address_same_as_shipping_address'] = 'required|' . Rule::in(['0', '1']);
            if (! $this->input('billing_address_same_as_shipping_address') || (! $isSaveOrderShippingAddress && auth('customer')->check() && ! $addressId)) {
                $rules['billing_address'] = 'array';
                $rules = array_merge($rules, EcommerceHelper::getCustomerAddressValidationRules('billing_address.'));
            } else {
                $billingAddressSameAsShippingAddress = true;
            }
        }

        if (! auth('customer')->check() && ($countDigitalProducts = EcommerceHelper::countDigitalProducts($products))) {
            $rules = array_merge($rules, EcommerceHelper::getCustomerAddressValidationRules('address.'));
            $rules['address.email'] = 'required|max:60|min:6';
            if ($countDigitalProducts == $products->count() && ! $billingAddressSameAsShippingAddress) {
                $rules = $this->removeRequired($rules, [
                    'address.country',
                    'address.state',
                    'address.city',
                    'address.address',
                    'address.phone',
                    'address.zip_code',
                ]);
            }
        }

        $isCreateAccount = ! auth('customer')->check() && $this->input('create_account') == 1;
        if ($isCreateAccount) {
            $rules['password'] = 'required|min:6';
            $rules['password_confirmation'] = 'required|same:password';
            $rules['address.email'] = 'required|max:60|min:6|email|unique:ec_customers,email';
            $rules['address.name'] = 'required|min:3|max:120';
        }

        return apply_filters(PROCESS_CHECKOUT_RULES_REQUEST_ECOMMERCE, $rules);
    }

    public function messages(): array
    {
        return apply_filters(PROCESS_CHECKOUT_MESSAGES_REQUEST_ECOMMERCE, [
            'address.name.required' => trans('plugins/ecommerce::order.address_name_required'),
            'address.phone.required' => trans('plugins/ecommerce::order.address_phone_required'),
            'address.email.required' => trans('plugins/ecommerce::order.address_email_required'),
            'address.email.unique' => trans('plugins/ecommerce::order.address_email_unique'),
            'address.state.required' => trans('plugins/ecommerce::order.address_state_required'),
            'address.city.required' => trans('plugins/ecommerce::order.address_city_required'),
            'address.country.required' => trans('plugins/ecommerce::order.address_country_required'),
            'address.address.required' => trans('plugins/ecommerce::order.address_address_required'),
            'address.zip_code.required' => trans('plugins/ecommerce::order.address_zipcode_required'),

            'billing_address.name.required' => trans('plugins/ecommerce::order.address_name_required'),
            'billing_address.phone.required' => trans('plugins/ecommerce::order.address_phone_required'),
            'billing_address.email.required' => trans('plugins/ecommerce::order.address_email_required'),
            'billing_address.email.unique' => trans('plugins/ecommerce::order.address_email_unique'),
            'billing_address.state.required' => trans('plugins/ecommerce::order.address_state_required'),
            'billing_address.city.required' => trans('plugins/ecommerce::order.address_city_required'),
            'billing_address.country.required' => trans('plugins/ecommerce::order.address_country_required'),
            'billing_address.address.required' => trans('plugins/ecommerce::order.address_address_required'),
            'billing_address.zip_code.required' => trans('plugins/ecommerce::order.address_zipcode_required'),
        ]);
    }

    public function attributes(): array
    {
        return [
            'address.name' => __('Name'),
            'address.phone' => __('Phone'),
            'address.email' => __('Email'),
            'address.state' => __('State'),
            'address.city' => __('City'),
            'address.country' => __('Country'),
            'address.address' => __('Address'),
            'address.zip_code' => __('Zipcode'),
        ];
    }

    public function removeRequired(array $rules, string|array $keys): array
    {
        if (! is_array($keys)) {
            $keys = [$keys];
        }
        foreach ($keys as $key) {
            if (! empty($rules[$key])) {
                $values = $rules[$key];
                if (is_string($values)) {
                    $explode = explode('|', $values);
                    if (($k = array_search('required', $explode)) !== false) {
                        unset($explode[$k]);
                    }
                    $explode[] = 'nullable';
                    $values = $explode;
                } elseif (is_array($values)) {
                    if (($k = array_search('required', $values)) !== false) {
                        unset($values[$k]);
                    }
                    $values[] = 'nullable';
                }
                $rules[$key] = $values;
            }
        }

        return $rules;
    }
}
