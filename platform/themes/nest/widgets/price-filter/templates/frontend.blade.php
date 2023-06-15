@if (is_plugin_active('ecommerce'))
    @php
        Theme::asset()->usePath()->add('jquery-ui-css', 'css/plugins/jquery-ui.css');
        Theme::asset()->container('footer')->usePath()->add('jquery-ui-js', 'js/plugins/jquery-ui.js');
        Theme::asset()->container('footer')->usePath()->add('jquery-ui-touch-punch-js', 'js/plugins/jquery.ui.touch-punch.min.js');
    @endphp
    <form action="{{ route('public.products') }}" method="GET">
        <div class="sidebar-widget price_range range mb-30 widget-filter-item" data-type="price">
            <h5 class="section-title style-1 mb-30">{{ $config['name'] }}</h5>
            <div class="price-filter">
                <div class="price-filter-inner">
                    <div class="slider-range"></div>
                    <input type="hidden"
                        class="min_price min-range"
                        name="min_price"
                        value="{{ request()->input('min_price', 0) }}"
                        data-min="0"
                        data-label="{{ __('Min price') }}"/>
                    <input type="hidden"
                        class="min_price max-range"
                        name="max_price"
                        value="{{ request()->input('max_price', theme_option('max_filter_price', 100000)) }}"
                        data-max="{{ theme_option('max_filter_price', 100000) }}"
                        data-label="{{ __('Max price') }}"/>
                    <div class="price_slider_amount">
                        <div class="label-input">
                            <span class="d-inline-block">{{ __('Range') }}: </span>
                            <span class="from d-inline-block"></span>
                            <span class="to d-inline-block"></span>
                        </div>
                    </div>
                </div>
            </div>
            <button class="btn btn-sm btn-default mt-3"><i class="fi-rs-filter mr-5"></i> {{ __('Filter') }}</button>
        </div>
    </form>
@endif
