<?php

namespace Theme\Nest\Http\Resources;

use EcommerceHelper;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;
use RvMedia;

class ProductResource extends JsonResource
{
    public function toArray($request): array|JsonSerializable|Arrayable
    {
        $defaultImage = RvMedia::getDefaultImage();

        $originalProduct = ! $this->is_variation ? $this : $this->original_product;

        $data = [
            'id' => $this->id,
            'name' => $this->name,
            'url' => $this->url,
            'image' => RvMedia::getImageUrl($this->image, 'product-thumb', false, $defaultImage),
            'hover_image' => RvMedia::getImageUrl($this->image, 'product-thumb', false, $defaultImage),
            'price' => format_price($this->price_with_taxes),
            'sale_price' => $this->front_sale_price !== $this->price ? format_price($this->front_sale_price_with_taxes) : null,
            'urls' => [
                'quick_view' => route('public.ajax.quick-view', $this->id),
            ],
        ];

        if ($this->categories->count()) {
            $category = $this->categories->sortByDesc('id')->first();
            $data['category'] = [
                'avg' => $category->url,
                'count' => $category->name,
            ];
        }

        if (EcommerceHelper::isReviewEnabled()) {
            if ($this->reviews_count) {
                $data['reviews'] = [
                    'avg' => $this->reviews_avg,
                    'count' => $this->reviews_count,
                ];
            } elseif ($originalProduct->reviews->count()) {
                $data['reviews'] = [
                    'avg' => $originalProduct->reviews->avg('star'),
                    'count' => $originalProduct->reviews->count(),
                ];
            }
        }

        if (is_plugin_active('marketplace') && $this->store->id) {
            $data['vendor'] = [
                'name' => $this->store->name,
                'url' => $this->store->url,
            ];
        }

        if ($this->isOutOfStock()) {
            $data['is_out_of_stock'] = true;
        } elseif ($this->productLabels->count()) {
            $data['product_labels'] = [];
            foreach ($this->productLabels as $label) {
                $data['product_labels'][] = [
                    'color' => $label->color,
                    'name' => $label->name,
                ];
            }
        } elseif ($this->front_sale_price !== $this->price) {
            $data['sale_percentage'] = get_sale_percentage($this->price, $this->front_sale_price);
        }

        if (EcommerceHelper::isCartEnabled()) {
            $data['urls']['add_to_cart'] = route('public.cart.add-to-cart');
        }

        if (EcommerceHelper::isWishlistEnabled()) {
            $data['urls']['wishlist'] = route('public.wishlist.add', $this->id);
        }

        if (EcommerceHelper::isCompareEnabled()) {
            $data['urls']['compare'] = route('public.compare.add', $this->id);
        }

        return $data;
    }
}
