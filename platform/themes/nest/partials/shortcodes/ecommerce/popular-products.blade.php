<section class="product-tabs section-padding position-relative">
    <popular-products url="{{ route('public.ajax.popular-products', ['limit' => $shortcode->limit]) }}" :per-row="{{ (int)$shortcode->per_row > 0 ? (int)$shortcode->per_row : 4 }}" title="{!! BaseHelper::clean($shortcode->title) !!}"></popular-products>
</section>
