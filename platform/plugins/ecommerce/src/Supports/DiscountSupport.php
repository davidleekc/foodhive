<?php

namespace Botble\Ecommerce\Supports;

use Botble\Ecommerce\Models\Discount;
use Botble\Ecommerce\Repositories\Interfaces\DiscountInterface;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class DiscountSupport
{
    protected Collection|array $promotions = [];

    public function promotionForProduct(array $productIds, array $productCollectionIds): ?Discount
    {
        if (! $this->promotions) {
            $this->getAvailablePromotions();
        }

        foreach ($this->promotions as $promotion) {
            switch ($promotion->target) {
                case 'specific-product':
                case 'product-variant':
                    foreach ($promotion->products as $product) {
                        if (in_array($product->id, $productIds)) {
                            return $promotion;
                        }
                    }

                    break;

                case 'group-products':
                    foreach ($promotion->productCollections as $productCollection) {
                        if (in_array($productCollection->id, $productCollectionIds)) {
                            return $promotion;
                        }
                    }

                    break;

                case 'customer':
                    foreach ($promotion->customers as $customer) {
                        if ($customer->id == (auth('customer')->check() ? auth('customer')->id() : -1)) {
                            return $promotion;
                        }
                    }

                    break;
            }
        }

        return null;
    }

    public function getAvailablePromotions(): Collection
    {
        if (! $this->promotions instanceof Collection) {
            $this->promotions = collect();
        }

        if ($this->promotions->count() == 0) {
            $this->promotions = app(DiscountInterface::class)
                ->getAvailablePromotions(['products', 'customers', 'productCollections'], true);
        }

        return $this->promotions;
    }

    public function afterOrderPlaced(string $couponCode, int|string|null $customerId = 0): void
    {
        $now = Carbon::now();

        $discount = app(DiscountInterface::class)
            ->getModel()
            ->where('code', $couponCode)
            ->where('type', 'coupon')
            ->where('start_date', '<=', $now)
            ->where(function (Builder $query) use ($now) {
                return $query
                    ->whereNull('end_date')
                    ->orWhere('end_date', '>', $now);
            })
            ->first();

        if ($discount) {
            $discount->total_used++;
            app(DiscountInterface::class)->createOrUpdate($discount);

            if (func_num_args() == 1) {
                $customerId = auth('customer')->check() ? auth('customer')->id() : 0;
            }

            if ($discount->target == 'once-per-customer' && $customerId) {
                $discount->usedByCustomers()->syncWithoutDetaching($customerId);
            }
        }
    }

    public function afterOrderCancelled(string $couponCode, int|string|null $customerId = 0): void
    {
        $discount = app(DiscountInterface::class)
            ->getModel()
            ->where('code', $couponCode)
            ->where('type', 'coupon')
            ->first();

        if ($discount) {
            $discount->total_used--;
            app(DiscountInterface::class)->createOrUpdate($discount);

            if (func_num_args() == 1) {
                $customerId = auth('customer')->check() ? auth('customer')->id() : 0;
            }

            if ($discount->target == 'once-per-customer' && $customerId) {
                $discount->usedByCustomers()->detach($customerId);
            }
        }
    }
}
