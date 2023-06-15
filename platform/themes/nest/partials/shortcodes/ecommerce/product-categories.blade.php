<section class="popular-categories section-padding" id="product-categories">
    <product-categories-component url="{{ route('public.ajax.product-categories', ['categories' => $shortcode->categories]) }}" :scroll-items="{{ (int)$shortcode->scroll_items > 0 ? (int)$shortcode->scroll_items : 8 }}" :title="'{!! BaseHelper::clean($shortcode->title) !!}'"></product-categories-component>
</section>
