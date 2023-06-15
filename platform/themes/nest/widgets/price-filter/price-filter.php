<?php

use Botble\Widget\AbstractWidget;

class PriceFilterWidget extends AbstractWidget
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
    protected $widgetDirectory = 'price-filter';

    public function __construct()
    {
        parent::__construct([
            'name' => __('Filter by price'),
            'description' => __('Filter products by price on sidebar'),
        ]);
    }
}
