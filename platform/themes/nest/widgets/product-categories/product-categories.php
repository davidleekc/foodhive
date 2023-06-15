<?php

use Botble\Widget\AbstractWidget;

class ProductCategoriesWidget extends AbstractWidget
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
    protected $widgetDirectory = 'product-categories';

    /**
     * ProductCategories constructor.
     */
    public function __construct()
    {
        parent::__construct([
            'name' => __('Product Categories'),
            'description' => __('Widget display product categories'),
            'number_display' => 10,
        ]);
    }
}
