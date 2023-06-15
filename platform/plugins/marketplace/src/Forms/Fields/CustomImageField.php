<?php

namespace Botble\Marketplace\Forms\Fields;

use Illuminate\Support\Arr;
use Kris\LaravelFormBuilder\Fields\FormField;
use MarketplaceHelper;

class CustomImageField extends FormField
{
    public function render(array $options = [], $showLabel = true, $showField = true, $showError = true): string
    {
        $options['attr'] = Arr::set($options['attr'], 'class', Arr::get($options['attr'], 'class') . 'form-control');

        return parent::render($options, $showLabel, $showField, $showError);
    }

    protected function getTemplate(): string
    {
        return MarketplaceHelper::viewPath('dashboard.forms.fields.custom-image');
    }
}
