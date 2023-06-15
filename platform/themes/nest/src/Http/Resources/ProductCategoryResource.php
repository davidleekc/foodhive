<?php

namespace Theme\Nest\Http\Resources;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;
use JsonSerializable;
use RvMedia;

class ProductCategoryResource extends JsonResource
{
    public static array $params = [];

    public static function collection($resource, array $data = []): AnonymousResourceCollection
    {
        static::$params = $data;

        return parent::collection($resource);
    }

    public function toArray($request): array|JsonSerializable|Arrayable
    {
        $defaultImage = RvMedia::getDefaultImage();

        if (Arr::get(static::$params, 'simple')) {
            return [
                'id' => $this->id,
                'name' => $this->name,
                'url' => $this->url,
                'icon_image' => RvMedia::getImageUrl($this->getMetaData('icon_image', true), null, false, $defaultImage),
            ];
        }

        return [
            'id' => $this->id,
            'name' => $this->name,
            'url' => $this->url,
            'image' => RvMedia::getImageUrl($this->image, null, false, $defaultImage),
            'thumbnail' => RvMedia::getImageUrl($this->image, 'product-thumb', false, $defaultImage),
            'products_count_text' => __(':count items', ['count' => $this->products_count]),
            'background_color' => $this->getMetaData('background_color', true),
        ];
    }
}
