<?php

namespace Botble\Ecommerce\Repositories\Caches;

use Botble\Ecommerce\Models\Product;
use Botble\Ecommerce\Repositories\Interfaces\ProductInterface;
use Botble\Support\Repositories\Caches\CacheAbstractDecorator;

class ProductCacheDecorator extends CacheAbstractDecorator implements ProductInterface
{
    public function getSearch($query, $paginate = 10)
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }

    public function getRelatedProductAttributes($product)
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }

    public function getProducts(array $params)
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }

    public function getProductsWithCategory(array $params)
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }

    public function getOnSaleProducts(array $params)
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }

    public function getProductVariations($configurableProductId, array $params = [])
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }

    public function getProductsByCollections(array $params)
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }

    public function getProductByBrands(array $params)
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }

    public function getProductByTags(array $params)
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }

    public function filterProducts(array $filters, array $params = [])
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }

    public function getProductsByCategories(array $params)
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }

    public function getProductsByIds(array $ids, array $params = [])
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }

    public function getProductsWishlist(int|string $customerId, array $params = [])
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }

    public function getProductsRecentlyViewed(int|string $customerId, array $params = [])
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }

    public function saveProductOptions(array $options, Product $product)
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }

    public function productsNeedToReviewByCustomer(int|string $customerId, int $limit = 12, array $orderIds = [])
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }
}
