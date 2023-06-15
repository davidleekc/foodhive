<?php

use Botble\Widget\AbstractWidget;

class InstallAppWidget extends AbstractWidget
{
    /**
     * The configuration array.
     *
     * @var array
     */
    protected $config = [];

    /**
     * @var string
     */
    protected $frontendTemplate = 'frontend';

    /**
     * @var string
     */
    protected $backendTemplate = 'backend';

    /**
     * @var string
     */
    protected $widgetDirectory = 'install-app';

    /**
     * InstallApp constructor.
     */
    public function __construct()
    {
        parent::__construct([
            'name' => __('Install App'),
            'description' => __('Widget display mobile apps links.'),
            'apps_description' => null,
            'ios_app_url' => null,
            'ios_app_image' => null,
            'android_app_url' => null,
            'android_app_image' => null,
            'payment_gateway_description' => null,
            'payment_gateway_image' => null,
        ]);
    }
}
