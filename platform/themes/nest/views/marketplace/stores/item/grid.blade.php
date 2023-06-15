<div class="col-lg-3 col-md-6 col-12 col-sm-6">
    <div class="vendor-wrap mb-40">
        <div class="vendor-img-action-wrap">
            <div class="vendor-img">
                <a href="{{ $store->url }}">
                    <img class="default-img" src="{{ RvMedia::getImageUrl($store->logo, 'medium', false, RvMedia::getDefaultImage()) }}" alt="{{ $store->name }}" />
                </a>
            </div>
        </div>
        <div class="vendor-content-wrap">
            <div class="d-flex justify-content-between align-items-end mb-30">
                <div>
                    <div class="product-category">
                        <span class="text-muted">{{ __('Since :year', ['year' => $store->created_at->format('Y')]) }}</span>
                    </div>
                    <h4 class="mb-5"><a href="{{ $store->url }}">{{ $store->name }}</a></h4>
                    @include(Theme::getThemeNamespace('views.marketplace.stores.partials.rating'))
                </div>

                <div class="mb-10">
                    <span class="font-small total-product">{{ __(':total products', ['total' => $store->products_count]) }}</span>
                </div>
            </div>

            <div class="vendor-info mb-30">
                @include(Theme::getThemeNamespace('views.marketplace.stores.partials.info'))
            </div>
            <a href="{{ $store->url }}" class="btn btn-xs">{{ __('Visit Store') }} <i class="fi-rs-arrow-small-right"></i></a>
        </div>
    </div>
</div>
