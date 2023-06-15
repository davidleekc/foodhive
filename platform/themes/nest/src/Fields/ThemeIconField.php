<?php

namespace Theme\Nest\Fields;

use Assets;
use Kris\LaravelFormBuilder\Fields\FormField;
use Theme;

class ThemeIconField extends FormField
{
    /**
     * {@inheritDoc}
     */
    protected function getTemplate()
    {
        Assets::addScriptsDirectly(Theme::asset()->url('js/icons-field.js'))
            ->addStylesDirectly(Theme::asset()->url('css/vendors/uicons-regular-straight.css'));

        return Theme::getThemeNamespace() . '::partials.fields.icons-field';
    }
}
