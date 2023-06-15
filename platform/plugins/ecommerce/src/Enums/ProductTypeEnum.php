<?php

namespace Botble\Ecommerce\Enums;

use Botble\Base\Supports\Enum;
use EcommerceHelper;
use Html;
use Illuminate\Support\HtmlString;

/**
 * @method static ProductTypeEnum PHYSICAL()
 * @method static ProductTypeEnum DIGITAL()
 */
class ProductTypeEnum extends Enum
{
    public const PHYSICAL = 'physical';
    public const DIGITAL = 'digital';

    public static $langPath = 'plugins/ecommerce::products.types';

    public function toHtml(): HtmlString|string
    {
        return match ($this->value) {
            self::PHYSICAL => Html::tag('span', self::PHYSICAL()->label(), ['class' => 'label-info status-label'])
                ->toHtml(),
            self::DIGITAL => Html::tag('span', self::DIGITAL()->label(), ['class' => 'label-primary status-label'])
                ->toHtml(),
            default => parent::toHtml(),
        };
    }

    public function toIcon(): string
    {
        if (! EcommerceHelper::isEnabledSupportDigitalProducts()) {
            return '';
        }

        return match ($this->value) {
            self::PHYSICAL => Html::tag('i', '', [
                'class' => 'fa-solid fa-suitcase-rolling text-primary',
                'title' => self::PHYSICAL()->label(),
            ])->toHtml(),
            self::DIGITAL => Html::tag('i', '', [
                'class' => 'fa-solid fa-microchip text-info',
                'title' => self::DIGITAL()->label(),
            ])
                ->toHtml(),
            default => Html::tag('i', '', ['class' => 'fa fa-camera'])
                ->toHtml(),
        };
    }
}
