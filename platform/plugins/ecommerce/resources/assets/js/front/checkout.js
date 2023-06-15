try {
    window.$ = window.jQuery = require('jquery');

    require('bootstrap');
} catch (e) {
}

import {CheckoutAddress} from './partials/address';
import {DiscountManagement} from './partials/discount';

class MainCheckout {
    constructor() {
        new CheckoutAddress().init();
        new DiscountManagement().init();
    }

    static showNotice(messageType, message, messageHeader = '') {
        toastr.clear();

        toastr.options = {
            closeButton: true,
            positionClass: 'toast-bottom-right',
            onclick: null,
            showDuration: 1000,
            hideDuration: 1000,
            timeOut: 10000,
            extendedTimeOut: 1000,
            showEasing: 'swing',
            hideEasing: 'linear',
            showMethod: 'fadeIn',
            hideMethod: 'fadeOut'
        };

        if (!messageHeader) {
            switch (messageType) {
                case 'error':
                    messageHeader = window.messages.error_header;
                    break;
                case 'success':
                    messageHeader = window.messages.success_header;
                    break;
            }
        }

        toastr[messageType](message, messageHeader);
    }

    static handleError(data, $container) {
        if (typeof (data.errors) !== 'undefined' && !_.isArray(data.errors)) {
            MainCheckout.handleValidationError(data.errors, $container);
        } else {
            if (typeof (data.responseJSON) !== 'undefined') {
                if (typeof (data.responseJSON.errors) !== 'undefined') {
                    if (data.status === 422) {
                        MainCheckout.handleValidationError(data.responseJSON.errors, $container);
                    }
                } else if (typeof (data.responseJSON.message) !== 'undefined') {
                    MainCheckout.showError(data.responseJSON.message);
                } else {
                    $.each(data.responseJSON, (index, el) => {
                        $.each(el, (key, item) => {
                            MainCheckout.showError(item);
                        });
                    });
                }
            } else {
                MainCheckout.showError(data.statusText);
            }
        }
    }

    static dotArrayToJs(str){
        let splittedStr = str.split('.');

        return splittedStr.length == 1 ? str : (splittedStr[0] + '[' + splittedStr.splice(1).join('][') + ']');
    }

    static clearInValidInputs($container){
        if (!$container) {
            $container = $(document);
        }
        $container.find('.field-is-invalid').find('.invalid-feedback').remove();
        $container.find('.field-is-invalid').find('.is-invalid').removeClass('is-invalid');
        $container.find('.field-is-invalid').removeClass('field-is-invalid is-invalid');
    }

    static handleValidationError(errors, $container) {
        let message = '';
        $.each(errors, (index, item) => {
            message += item + '<br />';

            let inputName = MainCheckout.dotArrayToJs(index);
            let $input = $('*[name="' + inputName + '"]');
            if ($container) {
                $input = $container.find('[name="' + inputName + '"]')
            }
            if ($input.closest('.form-group').length) {
                $input.closest('.form-group').addClass('field-is-invalid');
            } else {
                $input.addClass('field-is-invalid');
            }

            if ($input.hasClass('form-control')) {
                $input.addClass('is-invalid');
                if ($input.is('select') && $input.closest('.select--arrow').length) {
                    $input.closest('.select--arrow').addClass('is-invalid');
                    $input.closest('.select--arrow').after('<div class="invalid-feedback">' + item + '</div>');
                } else {
                    $input.after('<div class="invalid-feedback">' + item + '</div>');
                }
            }
        });
        MainCheckout.showError(message);
    }

    static showError(message, messageHeader = '') {
        this.showNotice('error', message, messageHeader);
    }

    static showSuccess(message, messageHeader = '') {
        this.showNotice('success', message, messageHeader);
    }

    init() {
        let shippingForm = '#main-checkout-product-info';
        let customerShippingAddressForm = '.customer-address-payment-form';
        let customerBillingAddressForm = '.customer-billing-address-form';

        let disablePaymentMethodsForm = () => {
            $('.payment-info-loading').show();
            $('.payment-checkout-btn').prop('disabled', true);
        }

        let enablePaymentMethodsForm = () => {
            $('.payment-info-loading').hide();
            $('.payment-checkout-btn').prop('disabled', false);

            document.dispatchEvent(new CustomEvent('payment-form-reloaded'));
        }

        let getBaseUrl = () => {
            let baseUrl = window.location.href;

            if (!baseUrl.includes('?')) {
                baseUrl = baseUrl + '?';
            } else {
                baseUrl = baseUrl + '&';
            }

            return baseUrl;
        }

        let reloadAddressForm = url => {
            const isAddressAvailable = $(customerShippingAddressForm + ' #address_id option:selected').val();

            const addressForm = $(customerShippingAddressForm).clone();
            const billingAddressForm = $(customerBillingAddressForm).clone();

            const selectedCountry = $(customerShippingAddressForm + ' #address_country option:selected').val();
            const selectedState = $(customerShippingAddressForm + ' #address_state option:selected').val();
            const selectedCity = $(customerShippingAddressForm + ' #address_city option:selected').val();

            const billingAddressSelectedCountry = $(customerBillingAddressForm + ' #billing-address-country option:selected').val();
            const billingAddressSelectedState = $(customerBillingAddressForm + ' #billing-address-state option:selected').val();
            const billingAddressSelectedCity = $(customerBillingAddressForm + ' #billing-address-city option:selected').val();
            const billingAddressId = $(customerBillingAddressForm + ' #billing_address_id option:selected').val();

            $('.shipping-info-loading').show();
            $(shippingForm).load(url, () => {
                if (!isAddressAvailable) {
                    $(customerShippingAddressForm).replaceWith(addressForm);
                    if (selectedCountry) {
                        $(customerShippingAddressForm + ' #address_country').val(selectedCountry);
                    }

                    if (selectedState) {
                        $(customerShippingAddressForm + ' #address_state').val(selectedState);
                    }

                    if (selectedCity) {
                        $(customerShippingAddressForm + ' #address_city').val(selectedCity);
                    }
                }

                $(customerBillingAddressForm).replaceWith(billingAddressForm);

                if (billingAddressSelectedCountry) {
                    $(customerBillingAddressForm + ' #billing-address-country').val(billingAddressSelectedCountry);
                }

                if (billingAddressSelectedState) {
                    $(customerBillingAddressForm + ' #billing-address-state').val(billingAddressSelectedState);
                }

                if (billingAddressSelectedCity) {
                    $(customerBillingAddressForm + ' #billing-address-city').val(billingAddressSelectedCity);
                }

                if ($(customerBillingAddressForm + ' #billing_address_id').length) {
                    $(customerBillingAddressForm + ' #billing_address_id').val(billingAddressId);
                }

                $('.shipping-info-loading').hide();
                enablePaymentMethodsForm();
            });
        };

        let loadShippingFeeAtTheFirstTime = () => {
            let shippingMethod = $(document).find('input[name=shipping_method]:checked').first();
            if (!shippingMethod.length) {
                shippingMethod = $(document).find('input[name=shipping_method]').first();
                shippingMethod.prop('checked', 'checked');
            }

            if (shippingMethod.length) {
                disablePaymentMethodsForm();

                $('.mobile-total').text('...');

                let params = {
                    shipping_method: shippingMethod.val(),
                    shipping_option: shippingMethod.data('option'),
                    payment_method: '',
                }
                let paymentMethod = $(document).find('input[name=payment_method]:checked').first();
                if (paymentMethod) {
                    params.payment_method = paymentMethod.val();
                }

                reloadAddressForm(getBaseUrl() + $.param(params) + ' ' + shippingForm + ' > *');
            }
        }

        loadShippingFeeAtTheFirstTime();

        let loadShippingFeeAtTheSecondTime = () => {
            const $marketplace = $('.checkout-products-marketplace');

            if (!$marketplace || !$marketplace.length) {
                return;
            }

            let shippingMethods = $(shippingForm).find('input.shipping_method_input');
            let methods = {
                shipping_method: {},
                shipping_option: {},
                payment_method: '',
                address: {
                    address_id: $('.address-control-item#address_id').val()
                },
            };

            if (shippingMethods.length) {
                let storeIds = [];

                shippingMethods.map((i, shm) => {
                    let val = $(shm).filter(':checked').val();
                    let sId = $(shm).data('id');

                    if (!storeIds.includes(sId)) {
                        storeIds.push(sId);
                    }

                    if (val) {
                        methods['shipping_method'][sId] = val;
                        methods['shipping_option'][sId] = $(shm).data('option');
                    }
                });

                if (Object.keys(methods['shipping_method']).length !== storeIds.length) {
                    shippingMethods.map((i, shm) => {
                        let sId = $(shm).data('id');
                        if (!methods['shipping_method'][sId]) {
                            methods['shipping_method'][sId] = $(shm).val();
                            methods['shipping_option'][sId] = $(shm).data('option');
                            $(shm).prop('checked', true);
                        }
                    });
                }
            }

            let paymentMethod = $(document).find('input[name=payment_method]:checked').first();
            if (paymentMethod.length) {
                methods.payment_method = paymentMethod.val();
            }

            disablePaymentMethodsForm();

            reloadAddressForm(getBaseUrl() + $.param(methods) + ' ' + shippingForm + ' > *');
        }

        loadShippingFeeAtTheSecondTime();

        $(document).on('change', 'input.shipping_method_input', () => {
            loadShippingFeeAtTheSecondTime();
        });

        $(document).on('change', 'input[name=shipping_method]', event => {
            // Fixed: set shipping_option value based on shipping_method change:
            const $this = $(event.currentTarget);
            $('input[name=shipping_option]').val($this.data('option'));

            disablePaymentMethodsForm();

            $('.mobile-total').text('...');

            let params = {
                shipping_method: $this.val(),
                shipping_option: $this.data('option'),
                payment_method: '',
                address: {
                    address_id: $('.address-control-item#address_id').val(),
                },
            }

            let paymentMethod = $(document).find('input[name=payment_method]:checked').first();
            if (paymentMethod.length) {
                params.payment_method = paymentMethod.val();
            }

            reloadAddressForm(getBaseUrl() + $.param(params) + ' ' + shippingForm + ' > *');
        });

        $(document).on('change', 'input[name=payment_method]', event => {
            const $this = $(event.currentTarget);

            disablePaymentMethodsForm();

            $('.mobile-total').text('...');

            let params = {
                payment_method: $this.val(),
            }

            reloadAddressForm(getBaseUrl() + $.param(params) + ' ' + shippingForm + ' > *');
        });

        let validatedFormFields = () => {
            if ($(shippingForm).find('.shipping_method_input').length &&
                ! $(shippingForm).find('.shipping_method_input:checked').val()
            ) {
                return false;
            }

            let addressId = $('#address_id').val();
            if (addressId && addressId !== 'new') {
                return true;
            }

            let validated = true;
            $.each($(document).find('.address-control-item-required'), (index, el) => {
                if (!$(el).val() || $(el).val() === 'null') {
                    validated = false;
                }
            });

            return validated;
        }

        $(document).on('change', customerShippingAddressForm + ' .address-control-item', event => {
            let _self = $(event.currentTarget);
            _self.closest('.form-group').find('.text-danger').remove();
            let $form = _self.closest('form');
            if (validatedFormFields()) {
                $.ajax({
                    type: 'POST',
                    cache: false,
                    url: $('#save-shipping-information-url').val(),
                    data: new FormData($form[0]),
                    contentType: false,
                    processData: false,
                    success: res => {
                        if (!res.error) {
                            disablePaymentMethodsForm();

                            let $wrapper = $(shippingForm);
                            if ($wrapper.length) {
                                $('.shipping-info-loading').show();
                                $wrapper.load(window.location.href + ' ' + shippingForm + ' > *', () => {
                                    $('.shipping-info-loading').hide();
                                    const isChecked = $wrapper.find('input[name=shipping_method]:checked');
                                    if (!isChecked) {
                                        $wrapper.find('input[name=shipping_method]:first-child').trigger('click'); // need to check again
                                    }
                                    enablePaymentMethodsForm();
                                });
                            }

                            loadShippingFeeAtTheSecondTime(); // marketplace
                        }
                    },
                    error: res => {
                        MainCheckout.handleError(res, $form);
                        console.log(res);
                    },
                });
            }
        });

        $(document).on('change', customerBillingAddressForm + ' .address-control-item', event => {
            let _self = $(event.currentTarget);
            let $form = _self.closest('form');
            let val = _self.find(':selected').val();
            if (val) {
                $('.billing-address-form-wrapper').hide();
            } else {
                $('.billing-address-form-wrapper').show();
            }
        });
    }
}

$(document).ready(() => {
    new MainCheckout().init();

    window.MainCheckout = MainCheckout;
});
