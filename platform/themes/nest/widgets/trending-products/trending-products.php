<?php

use Botble\Widget\AbstractWidget;

class TrendingProductsWidget extends AbstractWidget
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
    protected $widgetDirectory = 'trending-products';

    /**
     * TrendingProductsWidget constructor.
     */
    public function __construct()
    {
        parent::__construct([
            'name' => __('Trending products'),
            'description' => __('Trending products of ecommerce'),
            'number_display' => 4,
        ]);
    }
}
