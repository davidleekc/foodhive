<?php

namespace Botble\Ecommerce\Models;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use RvMedia;

class ProductAttribute extends BaseModel
{
    protected $table = 'ec_product_attributes';

    protected $fillable = [
        'title',
        'slug',
        'color',
        'status',
        'order',
        'attribute_set_id',
        'image',
        'is_default',
    ];

    protected $casts = [
        'status' => BaseStatusEnum::class,
    ];

    public function getAttributeSetIdAttribute(int|string|null $value): int|string|null
    {
        return $value;
    }

    public function productAttributeSet(): BelongsTo
    {
        return $this->belongsTo(ProductAttributeSet::class, 'attribute_set_id');
    }

    public function getGroupIdAttribute(int|string|null $value): int|string|null
    {
        return $value;
    }

    protected static function boot()
    {
        parent::boot();

        self::deleting(function (ProductAttribute $productAttribute) {
            ProductVariationItem::where('attribute_id', $productAttribute->id)->delete();
        });
    }

    public function productVariationItems(): HasMany
    {
        return $this->hasMany(ProductVariationItem::class, 'attribute_id');
    }

    public function getAttributeStyle(?ProductAttributeSet $attributeSet = null, array|Collection $productVariations = []): string
    {
        if ($attributeSet && $attributeSet->use_image_from_product_variation) {
            foreach ($productVariations as $productVariation) {
                $attribute = $productVariation->productAttributes->where('attribute_set_id', $attributeSet->id)->first();
                if ($attribute && $attribute->id == $this->id && $productVariation->product->image) {
                    return 'background-image: url(' . RvMedia::getImageUrl($productVariation->product->image) . '); background-size: cover; background-repeat: no-repeat; background-position: center;';
                }
            }
        }

        if ($this->image) {
            return 'background-image: url(' . RvMedia::getImageUrl($this->image) . '); background-size: cover; background-repeat: no-repeat; background-position: center;';
        }

        return 'background-color: ' . ($this->color ?: '#000') . ';';
    }
}
