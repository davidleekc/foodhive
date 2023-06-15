<div class="table">
    <table>
        <tr>
            <th style="text-align: left">
                {{ trans('plugins/ecommerce::products.product_image') }}
            </th>
            <th style="text-align: left">
                {{ trans('plugins/ecommerce::products.product_name') }}
            </th>
        </tr>

        @foreach ($order->digitalProducts() as $orderProduct)
            <tr>
                <td>
                    <img src="{{ RvMedia::getImageUrl($orderProduct->product_image, 'thumb') }}" alt="{{ $orderProduct->product_image }}" width="50">
                </td>
                <td>
                    <span>{{ $orderProduct->product_image }}</span>
                </td>
                <td>
                    <a href="{{ $orderProduct->download_hash_url }}">{{ __('Download') }}</a>
                </td>
            </tr>
        @endforeach
    </table><br>
</div>

