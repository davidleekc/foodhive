<?php

use Botble\Widget\AbstractWidget;

class SiteFeaturesWidget extends AbstractWidget
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
    protected $widgetDirectory = 'site-features';

    /**
     * SiteFeaturesWidget constructor.
     */
    public function __construct()
    {
        parent::__construct([
            'name' => __('Site features'),
            'description' => __('Display Site features on sidebar'),
            'data' => [],
        ]);
    }
}
