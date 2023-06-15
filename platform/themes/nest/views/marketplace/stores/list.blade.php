@php
    $coverImage = $store->getMetadata('cover_image', true) ?: theme_option('vendor_cover_default_image');
@endphp
<div class="archive-header-3 mt-30 mb-80"
    style="background-image: url({{ $coverImage ? RvMedia::getImageUrl($coverImage) : Theme::asset()->url('imgs/vendor/vendor-header-bg.png') }})">
    <div class="archive-header-3-inner">
        <div class="vendor-logo mr-50">
            <img src="{{ RvMedia::getImageUrl($store->logo, 'medium', false, RvMedia::getDefaultImage()) }}" alt="{{ $store->name }}" />
        </div>
        <div class="vendor-content">
            <div class="product-category">
                <span class="text-muted">{{ __('Since :year', ['year' => $store->created_at->format('Y')]) }}</span>
            </div>
            <h3 class="mb-5 text-white"><a href="{{ $store->url }}" class="text-white">{{ $store->name }}</a></h3>
            <div class="mb-15">
                @include(Theme::getThemeNamespace('views.marketplace.stores.partials.rating'))
            </div>
            <div class="row">
                <div class="col-lg-4">
                    <div class="vendor-des mb-15">
                        <p class="font-sm text-white">{{ $store->description }}</p>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="vendor-info text-white mb-15">
                        @include(Theme::getThemeNamespace('views.marketplace.stores.partials.info'))
                    </div>
                </div>
                <div class="col-lg-4">
                    @include(Theme::getThemeNamespace('views.marketplace.stores.partials.socials'), ['headerClass' => 'text-white'])
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row flex-row-reverse">
    <div class="col-lg-4-5 products-listing position-relative">
        @include(Theme::getThemeNamespace('views.marketplace.stores.items'), compact('products'))
    </div>
    <div class="col-lg-1-5 primary-sidebar sticky-sidebar">
        @include(Theme::getThemeNamespace('views.marketplace.stores.partials.sidebar'), ['isShowSearchForm' => true])
    </div>
</div>
