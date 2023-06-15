<?php

namespace Theme\Nest\Http\Resources;

use EcommerceHelper;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;
use RvMedia;

class ProductMiniResource extends JsonResource
{
    public function toArray($request): array|JsonSerializable|Arrayable
    {
        $originalProduct = ! $this->is_variation ? $this : $this->original_product;

        $data = [
            'id' => $originalProduct->id,
            'name' => $originalProduct->name,
            'url' => $originalProduct->url,
            'image' => RvMedia::getImageUrl($this->image ?: $originalProduct->image, 'product-thumb', false, RvMedia::getDefaultImage()),
            'price' => format_price($this->price_with_taxes),
            'sale_price' => $this->front_sale_price !== $this->price ? format_price($this->front_sale_price_with_taxes) : null,
        ];

        if (EcommerceHelper::isReviewEnabled() && $originalProduct->reviews_count) {
            $data = array_merge($data, [
                'reviews_avg' => $originalProduct->reviews_avg,
                'reviews_count' => $originalProduct->reviews_count,
            ]);
        }

        return $data;
    }
}
