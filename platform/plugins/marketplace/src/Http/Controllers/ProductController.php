<?php

namespace Botble\Marketplace\Http\Controllers;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Ecommerce\Repositories\Interfaces\ProductInterface;
use EmailHandler;
use MarketplaceHelper;

class ProductController extends BaseController
{
    public function approveProduct(int|string $id, ProductInterface $productRepository, BaseHttpResponse $response)
    {
        $product = $productRepository->findOrFail($id);

        $product->status = BaseStatusEnum::PUBLISHED;
        $product->approved_by = auth()->id();

        $product->save();

        if (MarketplaceHelper::getSetting('enable_product_approval', 1)) {
            $store = $product->store;

            EmailHandler::setModule(MARKETPLACE_MODULE_SCREEN_NAME)
                ->setVariableValues([
                    'store_name' => $store->name,
                ])
                ->sendUsingTemplate('product-approved', $store->email);
        }

        return $response->setMessage(trans('plugins/marketplace::store.approve_product_success'));
    }
}
