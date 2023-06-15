<?php

namespace Botble\Ecommerce\Supports;

use BaseHelper;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Supports\Helper;
use Botble\Ecommerce\Enums\OrderStatusEnum;
use Botble\Ecommerce\Enums\ProductTypeEnum;
use Botble\Ecommerce\Models\Customer;
use Botble\Ecommerce\Models\Product;
use Botble\Ecommerce\Repositories\Interfaces\ProductInterface;
use Botble\Ecommerce\Repositories\Interfaces\ProductVariationInterface;
use Botble\Ecommerce\Repositories\Interfaces\ReviewInterface;
use Botble\Location\Repositories\Interfaces\CityInterface;
use Botble\Location\Repositories\Interfaces\CountryInterface;
use Botble\Location\Repositories\Interfaces\StateInterface;
use Botble\Location\Rules\CityRule;
use Botble\Location\Rules\StateRule;
use Botble\Payment\Enums\PaymentMethodEnum;
use Carbon\Carbon;
use Cart;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Theme;

class EcommerceHelper
{
    protected array $availableCountries = [];

    public function isCartEnabled(): bool
    {
        return get_ecommerce_setting('shopping_cart_enabled', 1) == 1;
    }

    public function isWishlistEnabled(): bool
    {
        return get_ecommerce_setting('wishlist_enabled', 1) == 1;
    }

    public function isCompareEnabled(): bool
    {
        return get_ecommerce_setting('compare_enabled', 1) == 1;
    }

    public function isReviewEnabled(): bool
    {
        return get_ecommerce_setting('review_enabled', 1) == 1;
    }

    public function isOrderTrackingEnabled(): bool
    {
        return get_ecommerce_setting('order_tracking_enabled', 1) == 1;
    }

    public function isOrderAutoConfirmedEnabled(): bool
    {
        return get_ecommerce_setting('order_auto_confirmed', 0) == 1;
    }

    public function reviewMaxFileSize(bool $isConvertToKB = false): int
    {
        $size = (int)get_ecommerce_setting('review_max_file_size', 2);

        if (! $size) {
            $size = 2;
        } elseif ($size > 1024) {
            $size = 1024;
        }

        return $isConvertToKB ? $size * 1024 : $size;
    }

    public function reviewMaxFileNumber(): int
    {
        $number = (int)get_ecommerce_setting('review_max_file_number', 6);

        if (! $number) {
            $number = 1;
        } elseif ($number > 100) {
            $number = 100;
        }

        return $number;
    }

    public function getReviewsGroupedByProductId(int|string $productId, int $reviewsCount = 0): Collection
    {
        if ($reviewsCount) {
            $reviews = app(ReviewInterface::class)->getGroupedByProductId($productId);
        } else {
            $reviews = collect();
        }

        $results = collect();
        for ($i = 5; $i >= 1; $i--) {
            if ($reviewsCount) {
                $review = $reviews->firstWhere('star', $i);
                $starCount = $review ? $review->star_count : 0;
                if ($starCount > 0) {
                    $starCount = $starCount / $reviewsCount * 100;
                }
            } else {
                $starCount = 0;
            }

            $results[] = [
                'star' => $i,
                'count' => $starCount,
                'percent' => ((int)($starCount * 100)) / 100,
            ];
        }

        return $results;
    }

    public function isQuickBuyButtonEnabled(): bool
    {
        return get_ecommerce_setting('enable_quick_buy_button', 1) == 1;
    }

    public function getQuickBuyButtonTarget(): string
    {
        return get_ecommerce_setting('quick_buy_target_page', 'checkout');
    }

    public function isZipCodeEnabled(): bool
    {
        return get_ecommerce_setting('zip_code_enabled', '0') == 1;
    }

    public function isBillingAddressEnabled(): bool
    {
        return get_ecommerce_setting('billing_address_enabled', '0') == 1;
    }

    public function isDisplayProductIncludingTaxes(): bool
    {
        if (! $this->isTaxEnabled()) {
            return false;
        }

        return get_ecommerce_setting('display_product_price_including_taxes', '0') == 1;
    }

    public function isTaxEnabled(): bool
    {
        return get_ecommerce_setting('ecommerce_tax_enabled', 1) == 1;
    }

    public function getAvailableCountries(): array
    {
        if (count($this->availableCountries)) {
            return $this->availableCountries;
        }

        $countries = ['' => __('Select country...')];

        if ($this->loadCountriesStatesCitiesFromPluginLocation()) {
            $selectedCountries = app(CountryInterface::class)
                ->getModel()
                ->where('status', BaseStatusEnum::PUBLISHED)
                ->orderBy('order', 'ASC')
                ->orderBy('name', 'ASC')
                ->pluck('name', 'id')
                ->all();

            if (! empty($selectedCountries)) {
                $this->availableCountries = $countries + $selectedCountries;

                return $this->availableCountries;
            }
        }

        try {
            $selectedCountries = json_decode(get_ecommerce_setting('available_countries'), true);
        } catch (Exception) {
            $selectedCountries = [];
        }

        if (empty($selectedCountries)) {
            $this->availableCountries = $countries + Helper::countries();

            return $this->availableCountries;
        }

        foreach (Helper::countries() as $key => $item) {
            if (in_array($key, $selectedCountries)) {
                $countries[$key] = $item;
            }
        }
        $this->availableCountries = $countries;

        return $this->availableCountries;
    }

    public function getAvailableStatesByCountry(int|string|null $countryId): array
    {
        if (! $this->loadCountriesStatesCitiesFromPluginLocation()) {
            return [];
        }

        $condition = [
            'status' => BaseStatusEnum::PUBLISHED,
        ];

        if ($this->isUsingInMultipleCountries()) {
            $condition['country_id'] = $countryId;
        }

        return app(StateInterface::class)
            ->getModel()
            ->where($condition)
            ->orderBy('order', 'ASC')
            ->orderBy('name', 'ASC')
            ->pluck('name', 'id')
            ->all();
    }

    public function getAvailableCitiesByState(int|string|null $stateId): array
    {
        if (! $this->loadCountriesStatesCitiesFromPluginLocation()) {
            return [];
        }

        return app(CityInterface::class)
            ->getModel()
            ->where('status', BaseStatusEnum::PUBLISHED)
            ->where('state_id', $stateId)
            ->orderBy('order', 'ASC')
            ->orderBy('name', 'ASC')
            ->pluck('name', 'id')
            ->all();
    }

    public function getSortParams(): array
    {
        $sort = [
            'default_sorting' => __('Default'),
            'date_asc' => __('Oldest'),
            'date_desc' => __('Newest'),
            'price_asc' => __('Price: low to high'),
            'price_desc' => __('Price: high to low'),
            'name_asc' => __('Name: A-Z'),
            'name_desc' => __('Name : Z-A'),
        ];

        if ($this->isReviewEnabled()) {
            $sort += [
                'rating_asc' => __('Rating: low to high'),
                'rating_desc' => __('Rating: high to low'),
            ];
        }

        return $sort;
    }

    public function getShowParams(): array
    {
        return apply_filters('ecommerce_number_of_products_display_options', [
            12 => 12,
            24 => 24,
            36 => 36,
        ]);
    }

    public function getMinimumOrderAmount(): float
    {
        return (float)get_ecommerce_setting('minimum_order_amount', 0);
    }

    public function isEnabledGuestCheckout(): bool
    {
        return get_ecommerce_setting('enable_guest_checkout', 1) == 1;
    }

    public function showNumberOfProductsInProductSingle(): bool
    {
        return get_ecommerce_setting('show_number_of_products', 1) == 1;
    }

    public function showOutOfStockProducts(): bool
    {
        return get_ecommerce_setting('show_out_of_stock_products', 1) == 1;
    }

    public function getDateRangeInReport(Request $request): array
    {
        $startDate = Carbon::now()->subDays(29);
        $endDate = Carbon::now();

        if ($request->input('date_from')) {
            try {
                $startDate = Carbon::now()->createFromFormat('Y-m-d', $request->input('date_from'));
            } catch (Exception) {
                $startDate = Carbon::now()->subDays(29);
            }
        }

        if ($request->input('date_to')) {
            try {
                $endDate = Carbon::now()->createFromFormat('Y-m-d', $request->input('date_to'));
            } catch (Exception) {
                $endDate = Carbon::now();
            }
        }

        if ($endDate->gt(Carbon::now())) {
            $endDate = Carbon::now();
        }

        if ($startDate->gt($endDate)) {
            $startDate = Carbon::now()->subDays(29);
        }

        $predefinedRange = $request->input('predefined_range', trans('plugins/ecommerce::reports.ranges.last_30_days'));

        return [$startDate, $endDate, $predefinedRange];
    }

    public function getSettingPrefix(): ?string
    {
        return config('plugins.ecommerce.general.prefix');
    }

    public function isPhoneFieldOptionalAtCheckout(): bool
    {
        return get_ecommerce_setting('make_phone_field_at_the_checkout_optional', 0) == 1;
    }

    public function isEnableEmailVerification(): bool
    {
        return get_ecommerce_setting('verify_customer_email', 0) == 1;
    }

    public function disableOrderInvoiceUntilOrderConfirmed(): bool
    {
        return get_ecommerce_setting('disable_order_invoice_until_order_confirmed', 0) == 1;
    }

    public function getPhoneValidationRule(): string
    {
        $rule = BaseHelper::getPhoneValidationRule();

        if ($this->isPhoneFieldOptionalAtCheckout()) {
            return 'nullable|' . $rule;
        }

        return 'required|' . $rule;
    }

    public function getProductReviews(Product $product, int $star = 0, int $perPage = 10): LengthAwarePaginator
    {
        $condition = [
            'ec_reviews.status' => BaseStatusEnum::PUBLISHED,
        ];

        if ($star && $star >= 1 && $star <= 5) {
            $condition['ec_reviews.star'] = $star;
        }

        $ids = [$product->id];
        if ($product->variations->count()) {
            $ids = array_merge($ids, $product->variations->pluck('product_id')->toArray());
        }

        $reviews = app(ReviewInterface::class)
            ->getModel()
            ->select(['ec_reviews.*'])
            ->where($condition);

        if ($product->variations->count()) {
            $reviews
                ->whereHas('product.variations', function ($query) use ($ids) {
                    $query->whereIn('ec_product_variations.product_id', $ids);
                });
        } else {
            $reviews->where('ec_reviews.product_id', $product->id);
        }

        return $reviews
            ->with(['user', 'user.orders' => function ($query) use ($ids) {
                $query
                    ->where('ec_orders.status', OrderStatusEnum::COMPLETED)
                    ->whereHas('products', function ($query) use ($ids) {
                        $query->where('product_id', $ids);
                    })
                    ->orderBy('ec_orders.created_at', 'desc');
            }])
            ->orderBy('created_at', 'desc')
            ->paginate($perPage)
            ->onEachSide(1)
            ->appends(['star' => $star]);
    }

    public function getThousandSeparatorForInputMask(): string
    {
        return ',';
    }

    public function getDecimalSeparatorForInputMask(): string
    {
        return '.';
    }

    /**
     * @deprecated since 11/2022
     */
    public function withReviewsCount(): array
    {
        $withCount = [];
        if ($this->isReviewEnabled()) {
            $withCount = [
                'reviews',
                'reviews as reviews_avg' => function ($query) {
                    $query->select(DB::raw('avg(star)'));
                },
            ];
        }

        return $withCount;
    }

    public function withReviewsParams(): array
    {
        if (! $this->isReviewEnabled()) {
            return [
                'withCount' => [],
                'withAvg' => [null, null],
            ];
        }

        return [
            'withCount' => ['reviews'],
            'withAvg' => ['reviews as reviews_avg', 'star'],
        ];
    }

    public function loadCountriesStatesCitiesFromPluginLocation(): bool
    {
        if (! is_plugin_active('location')) {
            return false;
        }

        return get_ecommerce_setting('load_countries_states_cities_from_location_plugin', 0) == 1;
    }

    public function getCountryNameById(int|string|null $countryId): ?string
    {
        if (! $countryId) {
            return null;
        }

        if ($this->loadCountriesStatesCitiesFromPluginLocation()) {
            $countryName = app(CountryInterface::class)
                ->getModel()
                ->where('id', $countryId)
                ->value('name');

            if (! empty($countryName)) {
                return $countryName;
            }
        }

        return Helper::getCountryNameByCode($countryId);
    }

    public function getStates(?string $countryCode): array
    {
        if (! $countryCode || ! $this->loadCountriesStatesCitiesFromPluginLocation()) {
            return [];
        }

        return app(StateInterface::class)
            ->getModel()
            ->whereHas('country', function ($query) use ($countryCode) {
                return $query->where('code', $countryCode);
            })
            ->where('status', BaseStatusEnum::PUBLISHED)
            ->orderBy('order', 'ASC')
            ->orderBy('name', 'ASC')
            ->pluck('name', 'id')
            ->all();
    }

    public function getCities(int|string|null $stateId): array
    {
        if (! $stateId || ! $this->loadCountriesStatesCitiesFromPluginLocation()) {
            return [];
        }

        return app(StateInterface::class)
            ->getModel()
            ->where('state_id', $stateId)
            ->where('status', BaseStatusEnum::PUBLISHED)
            ->orderBy('order', 'ASC')
            ->orderBy('name', 'ASC')
            ->pluck('name', 'id')
            ->all();
    }

    public function isUsingInMultipleCountries(): bool
    {
        return count($this->getAvailableCountries()) > 2;
    }

    public function getFirstCountryId(): int|string
    {
        return Arr::first(array_filter(array_keys($this->getAvailableCountries())));
    }

    public function getCustomerAddressValidationRules(?string $prefix = ''): array
    {
        $rules = [
            $prefix . 'name' => 'required|min:3|max:120',
            $prefix . 'email' => 'email|nullable|max:60|min:6',
            $prefix . 'state' => 'required|max:120',
            $prefix . 'city' => 'required|max:120',
            $prefix . 'address' => 'required|max:120',
            $prefix . 'phone' => $this->getPhoneValidationRule(),
        ];

        if ($this->isUsingInMultipleCountries()) {
            $rules[$prefix . 'country'] = 'required|' . Rule::in(array_keys($this->getAvailableCountries()));
        }

        if ($this->loadCountriesStatesCitiesFromPluginLocation()) {
            $rules[$prefix . 'state'] = [
                'required',
                'numeric',
                new StateRule($prefix . 'country'),
            ];

            $rules[$prefix . 'city'] = [
                'required',
                'numeric',
                new CityRule($prefix . 'state'),
            ];
        }

        if ($this->isZipCodeEnabled()) {
            $rules[$prefix . 'zip_code'] = 'required|max:20';
        }

        return $rules;
    }

    public function isEnabledCustomerRecentlyViewedProducts(): bool
    {
        return get_ecommerce_setting('enable_customer_recently_viewed_products', 1) == 1;
    }

    public function maxCustomerRecentlyViewedProducts(): int
    {
        return (int)get_ecommerce_setting('max_customer_recently_viewed_products', 24);
    }

    public function handleCustomerRecentlyViewedProduct(Product $product): self
    {
        if (! $this->isEnabledCustomerRecentlyViewedProducts()) {
            return $this;
        }

        $max = $this->maxCustomerRecentlyViewedProducts();

        if (! auth('customer')->check()) {
            $instance = Cart::instance('recently_viewed');

            $first = $instance->search(function ($cartItem) use ($product) {
                return $cartItem->id == $product->id;
            })->first();

            if ($first) {
                $instance->update($first->rowId, 1);
            } else {
                $instance->add($product->id, $product->name, 1, $product->front_sale_price)->associate(Product::class);
            }

            if ($max) {
                $content = collect($instance->content());
                if ($content->count() > $max) {
                    $content
                        ->sortBy([['updated_at', 'desc']])
                        ->skip($max)
                        ->each(function ($cartItem) use ($instance) {
                            $instance->remove($cartItem->rowId);
                        });
                }
            }
        } else {
            /**
             * @var Customer $customer
             */
            $customer = auth('customer')->user();
            $viewedProducts = $customer->viewedProducts;
            $exists = $viewedProducts->firstWhere('id', $product->id);

            $removedIds = [];

            if ($max) {
                if ($exists) {
                    $max -= 1;
                }

                if ($viewedProducts->count() >= $max) {
                    $filtered = $viewedProducts;
                    if ($exists) {
                        $filtered = $filtered->filter(function ($item) use ($product) {
                            return $item->id != $product->id;
                        });
                    }

                    $removedIds += $filtered->skip($max - 1)->pluck('id')->toArray();
                }
            }

            if ($exists) {
                $removedIds[] = $product->id;
            }

            if ($removedIds) {
                $customer->viewedProducts()->detach($removedIds);
            }

            $customer->viewedProducts()->attach([$product->id]);
        }

        return $this;
    }

    public function getProductVariationInfo(Product $product): array
    {
        $productImages = $product->images;

        $productVariation = $product;

        $selectedAttrs = [];

        $productVariationRepository = app(ProductVariationInterface::class);

        if ($product->variations()->count()) {
            if ($product->is_variation) {
                $product = $product->original_product;
                $selectedAttrs = $productVariationRepository->getAttributeIdsOfChildrenProduct($product->id);
                if (count($productImages) == 0) {
                    $productImages = $product->images;
                }
            } else {
                $selectedAttrs = $product->defaultVariation->productAttributes;
            }

            $selectedAttrIds = array_unique($selectedAttrs->pluck('id')->toArray());

            $variationDefault = $productVariationRepository->getVariationByAttributes($product->id, $selectedAttrIds);

            if ($variationDefault) {
                $productVariation = app(ProductInterface::class)->getProductVariations($product->id, [
                    'condition' => [
                        'ec_product_variations.id' => $variationDefault->id,
                        'original_products.status' => BaseStatusEnum::PUBLISHED,
                    ],
                    'select' => [
                        'ec_products.id',
                        'ec_products.name',
                        'ec_products.quantity',
                        'ec_products.price',
                        'ec_products.sale_price',
                        'ec_products.allow_checkout_when_out_of_stock',
                        'ec_products.with_storehouse_management',
                        'ec_products.stock_status',
                        'ec_products.images',
                        'ec_products.sku',
                        'ec_products.description',
                        'ec_products.is_variation',
                    ],
                    'take' => 1,
                ]);
            }
        }

        return [$productImages, $productVariation, $selectedAttrs];
    }

    public function getProductsSearchBy(): array
    {
        $setting = get_ecommerce_setting('search_products_by');

        if (empty($setting)) {
            return ['name', 'sku', 'description'];
        }

        if (is_array($setting)) {
            return $setting;
        }

        return json_decode($setting, true);
    }

    public function validateOrderWeight(int|float $weight): float|int
    {
        return max($weight, config('plugins.ecommerce.order.default_order_weight'));
    }

    public function isFacebookPixelEnabled(): bool
    {
        return get_ecommerce_setting('facebook_pixel_enabled', 0) == 1;
    }

    public function isGoogleTagManagerEnabled(): bool
    {
        return get_ecommerce_setting('google_tag_manager_enabled', 0) == 1;
    }

    public function getReturnableDays(): int
    {
        return (int)get_ecommerce_setting('returnable_days', 30);
    }

    public function canCustomReturnProductQty(): int
    {
        return $this->allowPartialReturn();
    }

    public function allowPartialReturn(): int
    {
        return get_ecommerce_setting('can_custom_return_product_quantity', 0);
    }

    public function isAvailableShipping(Collection $products): bool
    {
        if (! $this->isEnabledSupportDigitalProducts()) {
            return true;
        }

        $count = $this->countDigitalProducts($products);

        return ! $count || $products->count() != $count;
    }

    public function countDigitalProducts(Collection $products): int
    {
        if (! $this->isEnabledSupportDigitalProducts()) {
            return 0;
        }

        return $products->where('product_type', ProductTypeEnum::DIGITAL)->count();
    }

    public function canCheckoutForDigitalProducts(Collection $products): bool
    {
        $digitalProducts = $this->countDigitalProducts($products);
        if ($digitalProducts && ! auth('customer')->check() && ! $this->allowGuestCheckoutForDigitalProducts()) {
            return false;
        }

        return true;
    }

    public function isEnabledSupportDigitalProducts(): bool
    {
        return ! ! get_ecommerce_setting('is_enabled_support_digital_products', 0);
    }

    public function allowGuestCheckoutForDigitalProducts(): bool
    {
        return (bool) get_ecommerce_setting('allow_guest_checkout_for_digital_products', 0);
    }

    public function isSaveOrderShippingAddress(Collection $products): bool
    {
        return $this->isAvailableShipping($products) || (! auth('customer')->check() && $this->allowGuestCheckoutForDigitalProducts());
    }

    public function productFilterParamsValidated(Request $request): bool
    {
        $validator = Validator::make($request->input(), [
            'q' => 'nullable|string|max:255',
            'max_price' => 'nullable|numeric',
            'min_price' => 'nullable|numeric',
            'attributes' => 'nullable|array',
            'categories' => 'nullable|array',
            'tags' => 'nullable|array',
            'brands' => 'nullable|array',
            'sort-by' => 'nullable|string',
        ]);

        return ! $validator->fails();
    }

    public function viewPath(string $view): string
    {
        $themeView = Theme::getThemeNamespace() . '::views.ecommerce.' . $view;

        if (view()->exists($themeView)) {
            return $themeView;
        }

        return 'plugins/ecommerce::themes.' . $view;
    }

    public function getOriginAddress(): array
    {
        return [
            'name' => get_ecommerce_setting('store_name'),
            'company' => get_ecommerce_setting('store_company'),
            'email' => get_ecommerce_setting('store_email'),
            'phone' => get_ecommerce_setting('store_phone'),
            'country' => get_ecommerce_setting('store_country'),
            'state' => get_ecommerce_setting('store_state'),
            'city' => get_ecommerce_setting('store_city'),
            'address' => get_ecommerce_setting('store_address'),
            'address_2' => '',
            'zip_code' => get_ecommerce_setting('store_zip_code'),
        ];
    }

    public function getShippingData(
        array|Collection $products,
        array $session,
        array $origin,
        float $orderTotal,
        ?string $paymentMethod = null
    ): array {
        $weight = 0;
        $items = [];
        foreach ($products as $product) {
            if (! $product->isTypeDigital()) {
                $cartItem = $product->cartItem;
                $weight += $product->weight * $cartItem->qty;
                $items[$cartItem->id] = [
                    'weight' => $product->weight,
                    'length' => $product->length,
                    'wide' => $product->wide,
                    'height' => $product->height,
                    'name' => $product->name,
                    'description' => $product->description,
                    'qty' => $cartItem->qty,
                    'price' => $product->price,
                ];
            }
        }

        $keys = ['name', 'company', 'address', 'country', 'state', 'city', 'zip_code', 'email', 'phone'];

        if ($this->isUsingInMultipleCountries()) {
            $country = Arr::get($session, 'country');
        } else {
            $country = $this->getFirstCountryId();
        }

        $data = [
            'address' => Arr::get($session, 'address'),
            'country' => $country,
            'state' => Arr::get($session, 'state'),
            'city' => Arr::get($session, 'city'),
            'weight' => $this->validateOrderWeight($weight),
            'order_total' => max($orderTotal, 0),
            'address_to' => Arr::only($session, $keys),
            'origin' => $origin,
            'items' => $items,
            'extra' => [
                'order_token' => session('tracked_start_checkout'),
            ],
            'payment_method' => $paymentMethod,
        ];

        if (is_plugin_active('payment') && $paymentMethod == PaymentMethodEnum::COD) {
            $data['extra']['COD'] = [
                'amount' => max($orderTotal, 0),
                'currency' => get_application_currency()->title,
            ];
        }

        return $data;
    }

    public function onlyAllowCustomersPurchasedToReview(): bool
    {
        return get_ecommerce_setting('only_allow_customers_purchased_to_review', '0') == '1';
    }

    public function isValidToProcessCheckout(): bool
    {
        return Cart::instance('cart')->rawSubTotal() >= $this->getMinimumOrderAmount();
    }
}
