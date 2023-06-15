<?php

namespace Botble\Location\Events;

use Botble\Base\Events\Event;
use Botble\Location\Models\City;
use Illuminate\Queue\SerializesModels;

class ImportedCityEvent extends Event
{
    use SerializesModels;

    public array $row = [];

    public City $city;

    public function __construct(array $row, City $city)
    {
        $this->row = $row;
        $this->city = $city;
    }
}
