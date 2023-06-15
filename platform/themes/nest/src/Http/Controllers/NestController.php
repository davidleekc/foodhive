<?php

namespace Theme\Nest\Http\Controllers;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Blog\Repositories\Interfaces\PostInterface;
use Botble\Ecommerce\Models\Product;
use Botble\Ecommerce\Repositories\Interfaces\FlashSaleInterface;
use Botble\Ecommerce\Repositories\Interfaces\ProductCategoryInterface;
use Botble\Ecommerce\Repositories\Interfaces\ProductInterface;
use Botble\Ecommerce\Services\Products\GetProductService;
use Botble\Payment\Enums\PaymentStatusEnum;
use Botble\Theme\Http\Controllers\PublicController;
use Cart;
use EcommerceHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Theme;
use Theme\Nest\Http\Resources\BrandResource;
use Theme\Nest\Http\Resources\PostResource;
use Theme\Nest\Http\Resources\ProductCategoryResource;
use Theme\Nest\Http\Resources\ProductMiniResource;
use Theme\Nest\Http\Resources\ProductResource;
use Theme\Nest\Http\Resources\ReviewResource;
use Theme\Nest\Http\Resources\TopSellingProductResource;

class NestController extends PublicController
{
    public function ajaxCart(Request $request, BaseHttpResponse $response)
    {
        if (! $request->ajax()) {
            return $response->setNextUrl(route('public.index'));
        }

        return $response->setData([
            'count' => Cart::instance('cart')->count(),
            'html' => Theme::partial('cart-panel'),
        ]);
    }

    public function getFeaturedProducts(Request $request, BaseHttpResponse $response)
    {
        if (! $request->ajax() || ! $request->wantsJson()) {
            return $response->setNextUrl(route('public.index'));
        }

        $data = [];

        $products = get_featured_products([
            'take' => (int)$request->input('limit', 8),
            'with' => [
                'slugable',
                'variations',
                'productLabels',
                'variationAttributeSwatchesForProductList',
            ],
        ] + EcommerceHelper::withReviewsParams());

        foreach ($products as $product) {
            $data[] = view(
                Theme::getThemeNamespace() . '::views.ecommerce.includes.product-item-small',
                compact('product')
            )->render();
        }

        return $response->setData($data);
    }

    public function ajaxGetPosts(Request $request, BaseHttpResponse $response, PostInterface $postRepository)
    {
        if (! $request->ajax() || ! $request->wantsJson()) {
            return $response->setNextUrl(route('public.index'));
        }

        $posts = $postRepository->getFeatured(4, ['slugable']);

        return $response
            ->setData(PostResource::collection($posts))
            ->toApiResponse();
    }

    public function getFeaturedProductCategories(Request $request, BaseHttpResponse $response)
    {
        if (! $request->ajax() || ! $request->wantsJson()) {
            return $response->setNextUrl(route('public.index'));
        }

        $categories = get_featured_product_categories([
            'with' => ['slugable', 'metadata'],
            'withCount' => ['products'],
        ]);

        return $response->setData(ProductCategoryResource::collection($categories));
    }

    public function ajaxGetFeaturedBrands(Request $request, BaseHttpResponse $response)
    {
        if (! $request->ajax() || ! $request->wantsJson()) {
            return $response->setNextUrl(route('public.index'));
        }

        $brands = get_featured_brands();

        return $response->setData(BrandResource::collection($brands));
    }

    public function ajaxGetProductReviews(
        $id,
        Request $request,
        BaseHttpResponse $response,
        ProductInterface $productRepository
    ) {
        if (! $request->ajax() || ! $request->wantsJson()) {
            return $response->setNextUrl(route('public.index'));
        }

        $product = $productRepository->getFirstBy([
            'id' => $id,
            'status' => BaseStatusEnum::PUBLISHED,
            'is_variation' => 0,
        ]);

        if (! $product) {
            abort(404);
        }

        $star = (int)$request->input('star');
        $perPage = (int)$request->input('per_page', 10);

        $reviews = EcommerceHelper::getProductReviews($product, $star, $perPage);

        if ($star) {
            $message = __(':total review(s) ":star star" for ":product"', [
                'total' => $reviews->total(),
                'product' => $product->name,
                'star' => $star,
            ]);
        } else {
            $message = __(':total review(s) for ":product"', [
                'total' => $reviews->total(),
                'product' => $product->name,
            ]);
        }

        return $response
            ->setData(ReviewResource::collection($reviews))
            ->setMessage($message)
            ->toApiResponse();
    }

    public function ajaxGetRelatedProducts(
        $id,
        Request $request,
        BaseHttpResponse $response,
        ProductInterface $productRepository
    ) {
        if (! $request->ajax() || ! $request->wantsJson()) {
            return $response->setNextUrl(route('public.index'));
        }

        $product = $productRepository->findOrFail($id);

        $products = get_related_products($product, (int)$request->input('limit'));

        $data = [];
        foreach ($products as $product) {
            $data[] = view(
                Theme::getThemeNamespace() . '::views.ecommerce.includes.product-item',
                compact('product')
            )->render();
        }

        return $response->setData($data);
    }

    public function ajaxGetFlashSales(
        Request $request,
        BaseHttpResponse $response,
        FlashSaleInterface $flashSaleRepository
    ) {
        if (! $request->ajax()) {
            return $response->setNextUrl(route('public.index'));
        }

        $flashSales = $flashSaleRepository->getModel()
            ->notExpired()
            ->where('status', BaseStatusEnum::PUBLISHED)
            ->with([
                'products' => function ($query) use ($request) {
                    $with = ['slugable', 'productCollections'];

                    if (is_plugin_active('marketplace')) {
                        $with = array_merge($with, ['store', 'store.slugable']);
                    }

                    $reviewParams = EcommerceHelper::withReviewsParams();

                    if (EcommerceHelper::isReviewEnabled()) {
                        $query->withAvg($reviewParams['withAvg'][0], $reviewParams['withAvg'][1]);
                    }

                    return $query
                        ->where('status', BaseStatusEnum::PUBLISHED)
                        ->limit((int)$request->input('limit', 2))
                        ->withCount($reviewParams['withCount'])
                        ->with($with);
                },
                'metadata',
            ])
            ->get();

        if (! $flashSales->count()) {
            return $response->setData([]);
        }

        $data = [];
        foreach ($flashSales as $flashSale) {
            foreach ($flashSale->products as $product) {
                if (! EcommerceHelper::showOutOfStockProducts() && $product->isOutOfStock()) {
                    continue;
                }

                $data[] = Theme::partial('flash-sale-product', compact('product', 'flashSale'));
            }
        }

        return $response->setData($data);
    }

    public function ajaxGetProducts(Request $request, BaseHttpResponse $response)
    {
        if (! $request->ajax() || ! $request->wantsJson()) {
            return $response->setNextUrl(route('public.index'));
        }

        $products = get_products_by_collections([
            'collections' => [
                'by' => 'id',
                'value_in' => [$request->input('collection_id')],
            ],
            'take' => 8,
            'with' => [
                'slugable',
                'variations',
                'productCollections',
                'variationAttributeSwatchesForProductList',
            ],
        ] + EcommerceHelper::withReviewsParams());

        $data = [];
        foreach ($products as $product) {
            $data[] = view(
                Theme::getThemeNamespace() . '::views.ecommerce.includes.product-item',
                compact('product')
            )->render();
        }

        return $response->setData($data);
    }

    public function ajaxGetProductsByCategoryId(
        Request $request,
        BaseHttpResponse $response,
        ProductInterface $productRepository
    ) {
        if (! $request->ajax() || ! $request->wantsJson()) {
            return $response->setNextUrl(route('public.index'));
        }

        $categoryId = $request->input('category_id');

        if (! $categoryId) {
            return $response;
        }

        $products = $productRepository->getProductsByCategories([
            'categories' => [
                'by' => 'id',
                'value_in' => [$categoryId],
            ],
            'take' => 8,
        ] + EcommerceHelper::withReviewsParams());

        $data = [];
        foreach ($products as $product) {
            $data[] = view(Theme::getThemeNamespace() . '::views.ecommerce.includes.product-item', compact('product'))
                ->render();
        }

        return $response->setData($data);
    }

    public function getQuickView(Request $request, $id, BaseHttpResponse $response)
    {
        if (! $request->ajax()) {
            return $response->setNextUrl(route('public.index'));
        }

        $product = get_products([
            'condition' => [
                'ec_products.id' => $id,
                'ec_products.status' => BaseStatusEnum::PUBLISHED,
            ],
            'take' => 1,
            'with' => [
                'slugable',
                'tags',
                'tags.slugable',
                'options' => function ($query) {
                    return $query->with('values');
                },
            ],
        ] + EcommerceHelper::withReviewsParams());

        if (! $product) {
            return $response->setNextUrl(route('public.index'));
        }

        list($productImages, $productVariation, $selectedAttrs) = EcommerceHelper::getProductVariationInfo($product);

        return $response->setData(Theme::partial('quick-view', compact('product', 'selectedAttrs', 'productImages', 'productVariation')));
    }

    public function ajaxGetProductCategories(Request $request, BaseHttpResponse $response)
    {
        if (! $request->ajax() || ! $request->wantsJson()) {
            return $response->setNextUrl(route('public.index'));
        }

        $params = array_merge([
            'condition' => [
                'ec_product_categories.status' => BaseStatusEnum::PUBLISHED,
            ],
            'take' => null,
            'order_by' => [
                'ec_product_categories.order' => 'DESC',
            ],
            'select' => ['*'],
            'with' => ['slugable', 'metadata'],
        ]);

        $categoryIds = array_filter(explode(',', $request->input('categories')));

        if (! empty($categoryIds)) {
            $params['condition'][] = ['ec_product_categories.id', 'IN', $categoryIds];
        }

        $categories = app(ProductCategoryInterface::class)->advancedGet($params);

        return $response->setData(ProductCategoryResource::collection($categories, ['simple' => true]));
    }

    public function ajaxTopProductsGroup(Request $request, BaseHttpResponse $response)
    {
        if (! $request->ajax() || ! $request->wantsJson()) {
            return $response->setNextUrl(route('public.index'));
        }

        $tabs = array_filter(explode(',', $request->input('tabs')));

        if (empty($tabs)) {
            $tabs = ['top-selling', 'trending-products', 'recent-added', 'top-rated'];
        }

        $limit = 4;

        $with = ['slugable', 'variationInfo', 'productCollections'];

        $data = [];

        if (in_array('top-selling', $tabs)) {
            $endDate = now();
            $startDate = now()->subDays((int) $request->input('top_selling_in_days', 30));

            $topSelling = app(ProductInterface::class)
                ->getModel()
                ->join('ec_order_product', 'ec_products.id', '=', 'ec_order_product.product_id')
                ->join('ec_orders', 'ec_orders.id', '=', 'ec_order_product.order_id')
                ->join('payments', 'payments.order_id', '=', 'ec_orders.id')
                ->where('payments.status', PaymentStatusEnum::COMPLETED)
                ->whereDate('ec_orders.created_at', '>=', $startDate)
                ->whereDate('ec_orders.created_at', '<=', $endDate)
                ->select([
                    'ec_products.*',
                    'ec_order_product.qty as qty',
                ])
                ->with($with)
                ->orderBy('ec_order_product.qty', 'DESC')
                ->distinct()
                ->limit($limit)
                ->get();

            if ($topSelling->count()) {
                $data[] = [
                    'title' => __('Top Selling'),
                    'products' => TopSellingProductResource::collection($topSelling),
                ];
            }
        }

        if (in_array('trending-products', $tabs)) {
            $trendingProducts = get_trending_products([
                'take' => $limit,
                'with' => $with,
            ] + EcommerceHelper::withReviewsParams());

            $data[] = [
                'title' => __('Trending Products'),
                'products' => ProductMiniResource::collection($trendingProducts),
            ];
        }

        if (in_array('recent-added', $tabs)) {
            $recentlyAdded = app(ProductInterface::class)->advancedGet([
                'condition' => [
                    'ec_products.status' => BaseStatusEnum::PUBLISHED,
                    'ec_products.is_variation' => 0,
                ],
                'order_by' => [
                    'ec_products.order' => 'ASC',
                    'ec_products.created_at' => 'DESC',
                ],
                'take' => $limit,
                'with' => $with,
            ] + EcommerceHelper::withReviewsParams());

            $data[] = [
                'title' => __('Recently Added'),
                'products' => ProductMiniResource::collection($recentlyAdded),
            ];
        }

        if (EcommerceHelper::isReviewEnabled() && in_array('top-rated', $tabs)) {
            $topRated = get_top_rated_products($limit, $with);

            if ($topRated->count()) {
                $data[] = [
                    'title' => __('Top Rated'),
                    'products' => ProductMiniResource::collection($topRated),
                ];
            }
        }

        return $response->setData($data);
    }

    public function ajaxPopularProducts(Request $request, BaseHttpResponse $response)
    {
        if (! $request->ajax() || ! $request->wantsJson()) {
            return $response->setNextUrl(route('public.index'));
        }

        $with = [
            'metadata',
            'slugable',
            'categories',
            'categories.slugable',
            'productLabels',
            'productCollections',
        ];

        if (is_plugin_active('marketplace')) {
            $with = array_merge($with, ['store', 'store.slugable']);
        }

        $reviewParams = EcommerceHelper::withReviewsParams();

        $query = app(ProductInterface::class)
            ->getModel()
            ->join('meta_boxes', function ($join) {
                $join
                    ->on('ec_products.id', '=', 'meta_boxes.reference_id')
                    ->where('meta_boxes.reference_type', '=', Product::class);
            })
            ->where([
                'meta_boxes.meta_key' => 'is_popular',
                'meta_boxes.meta_value' => '["1"]',
                'ec_products.status' => BaseStatusEnum::PUBLISHED,
                'ec_products.is_variation' => 0,
            ])
            ->with($with)
            ->orderBy('ec_products.created_at', 'DESC')
            ->orderBy('ec_products.order', 'ASC')
            ->withCount($reviewParams['withCount'])
            ->limit((int) $request->input('limit') ?: 8)
            ->select('ec_products.*')
            ->distinct();

        if (EcommerceHelper::isReviewEnabled()) {
            $query->withAvg($reviewParams['withAvg'][0], $reviewParams['withAvg'][1]);
        }

        $products = $query->get();

        return $response->setData(ProductResource::collection($products));
    }

    public function ajaxSearchProducts(
        Request $request,
        GetProductService $productService,
        BaseHttpResponse $response
    ) {
        if (! $request->ajax() || ! $request->wantsJson()) {
            return $response->setNextUrl(route('public.index'));
        }

        $request->merge(['num' => 12]);

        $with = [
            'slugable',
            'variations',
            'productCollections',
            'variationAttributeSwatchesForProductList',
        ];

        $products = $productService->getProduct($request, null, null, $with);

        $queries = $request->input();
        foreach ($queries as $key => $query) {
            if (! $query || $key == 'num' || (is_array($query) && ! Arr::get($query, 0))) {
                unset($queries[$key]);
            }
        }

        $total = $products->count();
        $message = $total != 1 ? __(':total Products found', compact('total')) : __(':total Product found', compact('total'));

        return $response
            ->setData(Theme::partial('ajax-search-results', compact('products', 'queries')))
            ->setMessage($message);
    }
}
