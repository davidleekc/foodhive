<section class="popular-categories section-padding">
    <featured-product-categories-component url="{{ route('public.ajax.featured-product-categories') }}" :scroll-items="{{ (int)$shortcode->scroll_items > 0 ? (int)$shortcode->scroll_items : 10 }}" :title="'{!! BaseHelper::clean($shortcode->title) !!}'"></featured-product-categories-component>
</section>
