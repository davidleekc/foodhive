<?php

namespace Theme\Nest\Http\Resources;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;
use RvMedia;

class BrandResource extends JsonResource
{
    public function toArray($request): array|JsonSerializable|Arrayable
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'website' => $this->website,
            'logo' => RvMedia::getImageUrl($this->logo, 'product-thumb', false, RvMedia::getDefaultImage()),
        ];
    }
}
