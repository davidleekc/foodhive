<?php

namespace Botble\Ecommerce\Http\Middleware;

use Closure;

use Illuminate\Http\Request;
use Botble\Ecommerce\Services\Footprints\TrackingFilterInterface;
use Botble\Ecommerce\Services\Footprints\TrackingLoggerInterface;

class CaptureFootprintsMiddleware
{
    public function __construct(protected TrackingFilterInterface $filter, protected TrackingLoggerInterface $logger)
    {
    }

    public function handle(Request $request, Closure $next)
    {
        if ($this->filter->shouldTrack($request)) {
            $request = $this->logger->track($request);
        }

        return $next($request);
    }
}
