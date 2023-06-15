<?php

namespace Botble\Ecommerce\Services\Footprints;

use Illuminate\Http\Request;

interface TrackingFilterInterface
{
    /**
     * Determine whether the request should be tracked.
     */
    public function shouldTrack(Request $request): bool;
}
