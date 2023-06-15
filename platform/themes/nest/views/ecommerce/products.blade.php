@php
    $layout = theme_option('product_list_layout');

    $requestLayout = request()->input('layout');
    if ($requestLayout && in_array($requestLayout, array_keys(get_product_single_layouts()))) {
        $layout = $requestLayout;
    }

    $layout = ($layout && in_array($layout, array_keys(get_product_single_layouts()))) ? $layout : 'product-full-width';
    Theme::layout($layout);

    Theme::asset()->usePath()->add('jquery-ui-css', 'css/plugins/jquery-ui.css');
    Theme::asset()->container('footer')->usePath()->add('jquery-ui-js', 'js/plugins/jquery-ui.js');
    Theme::asset()->container('footer')->usePath()->add('jquery-ui-touch-punch-js', 'js/plugins/jquery.ui.touch-punch.min.js');

    $products->loadMissing(['categories', 'categories.slugable']);
@endphp

<div class="col-lg-12 m-auto my-5">
    <a class="shop-filter-toggle" href="#">
        <span class="fi-rs-filter mr-5"></span>
        <span class="title">{{ __('Filters') }}</span>
        <i class="fi-rs-angle-small-up angle-up"></i>
        <i class="fi-rs-angle-small-down angle-down"></i>
    </a>
    <form action="{{ URL::current() }}" method="GET" id="products-filter-ajax">
        @if ($layout != 'product-full-width')
            <input type="hidden" name="layout" value="{{ $layout }}">
        @endif
        @include(Theme::getThemeNamespace() . '::views/ecommerce/includes/filters')
    </form>
</div>

<div class="products-listing position-relative">
    @include(Theme::getThemeNamespace() . '::views.ecommerce.includes.product-items', compact('products'))
</div>
