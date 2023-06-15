<?php

use Botble\Widget\AbstractWidget;

class GalleryWidget extends AbstractWidget
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
    protected $widgetDirectory = 'gallery';

    /**
     * GalleryWidget constructor.
     */
    public function __construct()
    {
        parent::__construct([
            'name' => __('Gallery'),
            'description' => __('Gallery of Simple Slider'),
            'slider_key' => null,
        ]);
    }
}
