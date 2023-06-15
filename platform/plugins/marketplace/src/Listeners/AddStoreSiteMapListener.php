<?php

namespace Botble\Marketplace\Listeners;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Marketplace\Repositories\Interfaces\StoreInterface;
use SiteMapManager;

class AddStoreSiteMapListener
{
    public function __construct(protected StoreInterface $storeRepository)
    {
    }

    public function handle(): void
    {
        SiteMapManager::add(route('public.stores'), '2022-09-14 00:00:00', '1', 'monthly');

        $stores = $this->storeRepository
            ->getModel()
            ->with('slugable')
            ->where('status', BaseStatusEnum::PUBLISHED)
            ->orderBy('created_at', 'desc')
            ->select(['id', 'name', 'updated_at'])
            ->get();

        foreach ($stores as $store) {
            if (! $store->slugable) {
                continue;
            }

            SiteMapManager::add($store->url, $store->updated_at, '0.8');
        }
    }
}
