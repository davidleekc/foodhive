<?php

namespace Botble\Ecommerce\Events;

use Botble\Base\Events\Event;
use Botble\Ecommerce\Models\Product;
use Illuminate\Queue\SerializesModels;

class ProductQuantityUpdatedEvent extends Event
{
    use SerializesModels;

    public Product $product;

    public function __construct(Product $product)
    {
        $this->product = $product;
    }
}
