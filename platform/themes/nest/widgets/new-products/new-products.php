<?php

use Botble\Widget\AbstractWidget;

class NewProductsWidget extends AbstractWidget
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
    protected $widgetDirectory = 'new-products';

    /**
     * NewProductsWidget constructor.
     */
    public function __construct()
    {
        parent::__construct([
            'name' => __('New products'),
            'description' => __('New products of ecommerce'),
            'number_display' => 3,
        ]);
    }
}
