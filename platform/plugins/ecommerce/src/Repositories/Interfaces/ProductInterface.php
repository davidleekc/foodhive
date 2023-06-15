<?php

namespace Botble\Ecommerce\Repositories\Interfaces;

use Botble\Ecommerce\Models\Product;
use Botble\Support\Repositories\Interfaces\RepositoryInterface;

interface ProductInterface extends RepositoryInterface
{
    /**
     * @deprecated
     */
    public function getSearch($query, $paginate = 10);

    public function getRelatedProductAttributes($product);

    public function getProducts(array $params);

    public function getProductsWithCategory(array $params);

    public function getOnSaleProducts(array $params);

    public function getProductVariations($configurableProductId, array $params = []);

    public function getProductsByCollections(array $params);

    public function getProductByBrands(array $params);

    public function getProductByTags(array $params);

    public function getProductsByCategories(array $params);

    public function filterProducts(array $filters, array $params = []);

    public function getProductsByIds(array $ids, array $params = []);

    public function getProductsWishlist(int|string $customerId, array $params = []);

    public function getProductsRecentlyViewed(int|string $customerId, array $params = []);

    public function saveProductOptions(array $options, Product $product);

    public function productsNeedToReviewByCustomer(int|string $customerId, int $limit = 12, array $orderIds = []);
}
