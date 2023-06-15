<?php

namespace Botble\Marketplace\Http\Controllers;

use Botble\Base\Http\Controllers\BaseController;
use Botble\Marketplace\Tables\VendorTable;

class VendorController extends BaseController
{
    public function index(VendorTable $table)
    {
        page_title()->setTitle(trans('plugins/marketplace::marketplace.vendors'));

        return $table->renderTable();
    }
}
