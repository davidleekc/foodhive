<ul class="font-sm mb-20">
    @if ($store->address)
        <li>
            <img src="{{ Theme::asset()->url('imgs/theme/icons/icon-location.svg') }}" alt="{{ __('Address') }}" />
            <strong class="d-inline-block">{{ __('Address') }}: </strong>
            <span class="d-inline-block">{{ $store->full_address }}</span>
        </li>
    @endif
    @if (!MarketplaceHelper::hideStorePhoneNumber() && $store->phone)
        <li>
            <img src="{{ Theme::asset()->url('imgs/theme/icons/icon-contact.svg') }}" alt="{{ __('Phone') }}" />
            <strong class="d-inline-block">{{ __('Call Us') }}:</strong>
            <span class="d-inline-block">{{ $store->phone }}</span>
        </li>
    @endif
</ul>
