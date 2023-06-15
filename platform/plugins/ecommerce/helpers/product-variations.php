<?php

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Ecommerce\Models\Product;
use Botble\Ecommerce\Repositories\Interfaces\ProductAttributeSetInterface;
use Botble\Ecommerce\Repositories\Interfaces\ProductVariationInterface;
use Botble\Ecommerce\Repositories\Interfaces\ProductVariationItemInterface;
use Botble\Ecommerce\Supports\RenderProductAttributeSetsOnSearchPageSupport;
use Botble\Ecommerce\Supports\RenderProductSwatchesSupport;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

if (! function_exists('render_product_swatches')) {
    function render_product_swatches(Product $product, array $params = []): string
    {
        $script = 'vendor/core/plugins/ecommerce/js/change-product-swatches.js';

        Theme::asset()->container('footer')->add('change-product-swatches', $script, ['jquery']);

        $selected = [];

        $params = array_merge([
            'selected' => $selected,
            'view' => 'plugins/ecommerce::themes.attributes.swatches-renderer',
        ], $params);

        $support = app(RenderProductSwatchesSupport::class);

        $html = $support->setProduct($product)->render($params);

        if (! request()->ajax()) {
            return $html;
        }

        return $html . Html::script($script)->toHtml();
    }
}

if (! function_exists('render_product_swatches_filter')) {
    function render_product_swatches_filter(array $params = []): string
    {
        return app(RenderProductAttributeSetsOnSearchPageSupport::class)->render($params);
    }
}

if (! function_exists('get_ecommerce_attribute_set')) {
    function get_ecommerce_attribute_set(): LengthAwarePaginator|Collection
    {
        return app(ProductAttributeSetInterface::class)
            ->advancedGet([
                'condition' => [
                    'status' => BaseStatusEnum::PUBLISHED,
                    'is_searchable' => 1,
                ],
                'order_by' => [
                    'order' => 'ASC',
                ],
                'with' => [
                    'attributes',
                ],
            ]);
    }
}

if (! function_exists('get_parent_product')) {
    function get_parent_product(int|string $variationId, array $with = ['slugable']): ?Product
    {
        return app(ProductVariationInterface::class)->getParentOfVariation($variationId, $with);
    }
}

if (! function_exists('get_parent_product_id')) {
    function get_parent_product_id(int|string $variationId): ?int
    {
        $parent = get_parent_product($variationId);

        return $parent?->id;
    }
}

if (! function_exists('get_product_info')) {
    function get_product_info(int|string $variationId): Collection
    {
        return app(ProductVariationItemInterface::class)->getVariationsInfo([$variationId]);
    }
}

if (! function_exists('get_product_attributes')) {
    function get_product_attributes(int|string $productId): Collection
    {
        return app(ProductVariationItemInterface::class)->getProductAttributes($productId);
    }
}
