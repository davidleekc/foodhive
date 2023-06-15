<?php

namespace Botble\Shippo\Commands;

use Botble\Ecommerce\Models\Address;
use Botble\Ecommerce\Models\StoreLocator;
use Botble\Location\Models\City;
use Botble\Location\Models\Country;
use Botble\Location\Models\State;
use Botble\Marketplace\Models\Store;
use Botble\Setting\Models\Setting;
use Illuminate\Console\Command;
use Illuminate\Console\ConfirmableTrait;
use Illuminate\Support\Facades\DB;
use Location;
use Throwable;

class InitShippoCommand extends Command
{
    use ConfirmableTrait;

    protected $signature = 'cms:shippo:init {--key= : The Test API Token to use} {--force : Force the operation to run when in production}';

    protected $description = 'Shippo Initialization';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (! $this->confirmToProceed()) {
            return 1;
        }

        $key = $this->option('key');

        $settings = [
            [
                'key' => 'ecommerce_load_countries_states_cities_from_location_plugin',
                'value' => '1',
            ],
            [
                'key' => 'ecommerce_zip_code_enabled',
                'value' => '1',
            ],
            [
                'key' => 'shipping_shippo_test_key',
                'value' => $key,
            ],
            [
                'key' => 'shipping_shippo_status',
                'value' => $key ? '1' : '0',
            ],
        ];

        $this->loadLocation();

        $city = null;
        $country = Country::where(['code' => 'US'])->first();
        if ($country) {
            $city = City::where(['name' => 'San Francisco', 'country_id' => $country->id])->first();
        }

        if ($city) {
            $countryId = (string)$city->country_id;
            $stateId = (string)$city->state_id;
            $cityId = (string)$city->id;
            $zipCode = '94117';
            $address = '215 Clayton St.';
            $phone = '+1 555 341 9393';
            $storeLocator = StoreLocator::where(['is_shipping_location' => 1, 'is_primary' => 1])->first();
            if ($storeLocator) {
                $storeLocator->update([
                    'phone' => $phone,
                    'address' => $address,
                    'country' => $countryId,
                    'state' => $stateId,
                    'city' => $cityId,
                ]);

                $settings = array_merge($settings, [
                    [
                        'key' => 'ecommerce_store_phone',
                        'value' => $phone,
                    ],
                    [
                        'key' => 'ecommerce_store_address',
                        'value' => $address,
                    ],
                    [
                        'key' => 'ecommerce_store_country',
                        'value' => $countryId,
                    ],
                    [
                        'key' => 'ecommerce_store_state',
                        'value' => $stateId,
                    ],
                    [
                        'key' => 'ecommerce_store_city',
                        'value' => $cityId,
                    ],
                    [
                        'key' => 'ecommerce_store_zip_code',
                        'value' => $zipCode,
                    ],
                ]);

                $this->info('Updated store locator id: ' . $storeLocator->id);
            }

            if (is_plugin_active('marketplace')) {
                $store = Store::first();
                if ($store) {
                    $store->update([
                        'country' => $countryId,
                        'state' => $stateId,
                        'city' => $cityId,
                        'zip_code' => $zipCode,
                        'address' => $address,
                        'phone' => $phone,
                    ]);

                    $this->info('Updated store id: ' . $store->id);
                }
            }

            $city2 = City::where(['name' => 'San Diego', 'country_id' => '1'])->first();
            if ($city2) {
                $address = Address::where(['is_default' => 1])->first();
                $address->update([
                    'phone' => '+1 555 341 9393',
                    'country' => $city2->country_id,
                    'state' => $city2->state_id,
                    'city' => $city2->id,
                    'address' => '2920 Zoo Drive',
                    'zip_code' => '92101',
                ]);

                $this->info('Updated address customer id: ' . $address->customer_id);
            }
        }

        Setting::whereIn('key', collect($settings)->pluck('key')->all())->delete();

        Setting::insert($settings);

        $this->info('Shippo configuration initialized successfully!');
    }

    public function loadLocation()
    {
        $country = Country::where(['code' => 'US'])->first();
        if (! $country) {
            City::truncate();
            State::truncate();
            Country::truncate();
            DB::table('cities_translations')->truncate();
            DB::table('states_translations')->truncate();
            DB::table('countries_translations')->truncate();

            try {
                Location::downloadRemoteLocation('us');
                $this->info('Loaded location successfully!');
            } catch (Throwable) {
                $this->warn('Cannot load location!');
            }
        }
    }
}
