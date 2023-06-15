<div class="customer-address-payment-form">

    @if (EcommerceHelper::isEnabledGuestCheckout() && ! auth('customer')->check())
        <div class="form-group mb-3">
            <p>{{ __('Already have an account?') }} <a href="{{ route('customer.login') }}">{{ __('Login') }}</a></p>
        </div>
    @endif

    @if (auth('customer')->check())
        <div class="form-group mb-3">
            @if ($isAvailableAddress)
                <label class="control-label mb-2" for="address_id">{{ __('Select available addresses') }}:</label>
            @endif
            @php
                $oldSessionAddressId = old('address.address_id', $sessionAddressId);
            @endphp
            <div class="list-customer-address @if (! $isAvailableAddress) d-none @endif">
                <div class="select--arrow">
                    <select name="address[address_id]" class="form-control address-control-item" id="address_id">
                        <option value="new" @selected ($oldSessionAddressId == 'new')>{{ __('Add new address...') }}</option>
                        @if ($isAvailableAddress)
                            @foreach ($addresses as $address)
                                <option value="{{ $address->id }}" @selected ($oldSessionAddressId == $address->id)>{{ $address->full_address }}</option>
                            @endforeach
                        @endif
                    </select>
                    <i class="fas fa-angle-down"></i>
                </div>
                <br>
                <div class="address-item-selected @if (! $sessionAddressId) d-none @endif">
                    @if ($isAvailableAddress && $oldSessionAddressId != 'new')
                        @if ($oldSessionAddressId && $addresses->contains('id', $oldSessionAddressId))
                            @include('plugins/ecommerce::orders.partials.address-item', ['address' => $addresses->firstWhere('id', $oldSessionAddressId)])
                        @elseif ($defaultAddress = get_default_customer_address())
                            @include('plugins/ecommerce::orders.partials.address-item', ['address' => $defaultAddress])
                        @else
                            @include('plugins/ecommerce::orders.partials.address-item', ['address' => Arr::first($addresses)])
                        @endif
                    @endif
                </div>
                <div class="list-available-address d-none">
                    @if ($isAvailableAddress)
                        @foreach($addresses as $address)
                            <div class="address-item-wrapper" data-id="{{ $address->id }}">
                                @include('plugins/ecommerce::orders.partials.address-item', compact('address'))
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    @endif

    <div class="address-form-wrapper @if (auth('customer')->check() && $oldSessionAddressId !== 'new' && $isAvailableAddress) d-none @endif">
        <div class="row">
            <div class="col-12">
                <div class="form-group mb-3 @error('address.name') has-error @enderror">
                    <input type="text" name="address[name]" id="address_name" placeholder="{{ __('Full Name') }}"
                        class="form-control address-control-item address-control-item-required checkout-input"
                        value="{{ old('address.name', Arr::get($sessionCheckoutData, 'name')) }}">
                    {!! Form::error('address.name', $errors) !!}
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8 col-12">
                <div class="form-group  @error('address.email') has-error @enderror">
                    <input type="email" name="address[email]" id="address_email" placeholder="{{ __('Email') }}"
                        class="form-control address-control-item address-control-item-required checkout-input"
                        value="{{ old('address.email', Arr::get($sessionCheckoutData, 'email')) }}">
                    {!! Form::error('address.email', $errors) !!}
                </div>
            </div>
            <div class="col-lg-4 col-12">
                <div class="form-group  @error('address.phone') has-error @enderror">
                    {!! Form::phoneNumber('address[phone]', old('address.phone', Arr::get($sessionCheckoutData, 'phone')), ['id' => 'address_phone', 'class' => 'form-control address-control-item ' . (!EcommerceHelper::isPhoneFieldOptionalAtCheckout() ? 'address-control-item-required' : '') . ' checkout-input']) !!}
                    {!! Form::error('address.phone', $errors) !!}
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="form-group mb-3 @error('address.country') has-error @enderror">
                    @if (EcommerceHelper::isUsingInMultipleCountries())
                        <div class="select--arrow">
                            <select name="address[country]" class="form-control address-control-item address-control-item-required"
                                data-form-parent=".customer-address-payment-form" id="address_country" data-type="country">
                                @foreach(EcommerceHelper::getAvailableCountries() as $countryCode => $countryName)
                                    <option value="{{ $countryCode }}" @if (old('address.country', Arr::get($sessionCheckoutData, 'country')) == $countryCode) selected @endif>{{ $countryName }}</option>
                                @endforeach
                            </select>
                            <i class="fas fa-angle-down"></i>
                        </div>
                    @else
                        <input type="hidden" name="address[country]" id="address_country" value="{{ EcommerceHelper::getFirstCountryId() }}">
                    @endif
                    {!! Form::error('address.country', $errors) !!}
                </div>
            </div>

            <div class="col-sm-6 col-12">
                <div class="form-group mb-3 @error('address.state') has-error @enderror">
                    @if (EcommerceHelper::loadCountriesStatesCitiesFromPluginLocation())
                        <div class="select--arrow">
                            <select name="address[state]" class="form-control address-control-item address-control-item-required"
                                data-form-parent=".customer-address-payment-form" id="address_state" data-type="state" data-url="{{ route('ajax.states-by-country') }}">
                                <option value="">{{ __('Select state...') }}</option>
                                @if (old('address.country', Arr::get($sessionCheckoutData, 'country')) || !EcommerceHelper::isUsingInMultipleCountries())
                                    @foreach(EcommerceHelper::getAvailableStatesByCountry(old('address.country', Arr::get($sessionCheckoutData, 'country'))) as $stateId => $stateName)
                                        <option value="{{ $stateId }}" @if (old('address.state', Arr::get($sessionCheckoutData, 'state')) == $stateId) selected @endif>{{ $stateName }}</option>
                                    @endforeach
                                @endif
                            </select>
                            <i class="fas fa-angle-down"></i>
                        </div>
                    @else
                        <input id="address_state" type="text" class="form-control address-control-item address-control-item-required checkout-input" placeholder="{{ __('State') }}" name="address[state]" value="{{ old('address.state', Arr::get($sessionCheckoutData, 'state')) }}">
                    @endif
                    {!! Form::error('address.state', $errors) !!}
                </div>
            </div>

            <div class="col-sm-6 col-12">
                <div class="form-group  @error('address.city') has-error @enderror">
                    @if (EcommerceHelper::loadCountriesStatesCitiesFromPluginLocation())
                        <div class="select--arrow">
                            <select name="address[city]" class="form-control address-control-item address-control-item-required" id="address_city" data-type="city" data-url="{{ route('ajax.cities-by-state') }}">
                                <option value="">{{ __('Select city...') }}</option>
                                @if (old('address.state', Arr::get($sessionCheckoutData, 'state')))
                                    @foreach(EcommerceHelper::getAvailableCitiesByState(old('address.state', Arr::get($sessionCheckoutData, 'state'))) as $cityId => $cityName)
                                        <option value="{{ $cityId }}" @if (old('address.city', Arr::get($sessionCheckoutData, 'city')) == $cityId) selected @endif>{{ $cityName }}</option>
                                    @endforeach
                                @endif
                            </select>
                            <i class="fas fa-angle-down"></i>
                        </div>
                    @else
                        <input id="address_city" type="text" class="form-control address-control-item address-control-item-required checkout-input" placeholder="{{ __('City') }}" name="address[city]" value="{{ old('address.city', Arr::get($sessionCheckoutData, 'city')) }}">
                    @endif
                    {!! Form::error('address.city', $errors) !!}
                </div>
            </div>

            <div class="col-12">
                <div class="form-group mb-3 @error('address.address') has-error @enderror">
                    <input id="address_address" type="text" class="form-control address-control-item address-control-item-required checkout-input" placeholder="{{ __('Address') }}" name="address[address]" value="{{ old('address.address', Arr::get($sessionCheckoutData, 'address')) }}">
                    {!! Form::error('address.address', $errors) !!}
                </div>
            </div>

            @if (EcommerceHelper::isZipCodeEnabled())
                <div class="col-12">
                    <div class="form-group mb-3 @error('address.zip_code') has-error @enderror">
                        <input id="address_zip_code" type="text"
                            class="form-control address-control-item address-control-item-required checkout-input"
                            placeholder="{{ __('Zip code') }}" name="address[zip_code]"
                            value="{{ old('address.zip_code', Arr::get($sessionCheckoutData, 'zip_code')) }}">
                        {!! Form::error('address.zip_code', $errors) !!}
                    </div>
                </div>
            @endif
        </div>
    </div>

    @if (! auth('customer')->check())
        <div class="form-group mb-3">
            <input type="checkbox" name="create_account" value="1" id="create_account" @if (old('create_account') == 1) checked @endif>
            <label for="create_account" class="control-label ps-2">{{ __('Register an account with above information?') }}</label>
        </div>

        <div class="password-group @if (! $errors->has('password') && ! $errors->has('password_confirmation')) d-none @endif">
            <div class="row">
                <div class="col-md-6 col-12">
                    <div class="form-group  @error('password') has-error @enderror">
                        <input id="password" type="password" class="form-control checkout-input" name="password" autocomplete="password" placeholder="{{ __('Password') }}">
                        {!! Form::error('password', $errors) !!}
                    </div>
                </div>

                <div class="col-md-6 col-12">
                    <div class="form-group @error('password_confirmation') has-error @enderror">
                        <input id="password-confirm" type="password" class="form-control checkout-input" autocomplete="password-confirmation" placeholder="{{ __('Password confirmation') }}" name="password_confirmation">
                        {!! Form::error('password_confirmation', $errors) !!}
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
