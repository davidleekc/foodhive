<?php

namespace Botble\Marketplace\Forms\Fields;

use Kris\LaravelFormBuilder\Fields\FormField;
use MarketplaceHelper;

class CustomImagesField extends FormField
{
    protected function getTemplate(): string
    {
        return MarketplaceHelper::viewPath('dashboard.forms.fields.custom-images');
    }
}
