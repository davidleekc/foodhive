<?php

namespace Botble\Marketplace\Enums;

use Botble\Base\Supports\Enum;
use Html;
use Illuminate\Support\HtmlString;

/**
 * @method static PayoutPaymentMethodsEnum BANK_TRANSFER()
 * @method static PayoutPaymentMethodsEnum PAYPAL()
 */
class PayoutPaymentMethodsEnum extends Enum
{
    public const BANK_TRANSFER = 'bank_transfer';
    public const PAYPAL = 'paypal';

    public static $langPath = 'plugins/marketplace::marketplace.payout_payment_methods';

    public function toHtml(): HtmlString|string
    {
        return match ($this->value) {
            self::BANK_TRANSFER => Html::tag(
                'span',
                self::BANK_TRANSFER()->label(),
                ['class' => 'label-info status-label']
            )
                ->toHtml(),
            self::PAYPAL => Html::tag('span', self::PAYPAL()->label(), ['class' => 'label-primary status-label'])
                ->toHtml(),
            default => parent::toHtml(),
        };
    }
}
