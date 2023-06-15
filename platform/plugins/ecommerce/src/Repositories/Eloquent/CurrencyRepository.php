<?php

namespace Botble\Ecommerce\Repositories\Eloquent;

use Botble\Ecommerce\Repositories\Interfaces\CurrencyInterface;
use Botble\Support\Repositories\Eloquent\RepositoriesAbstract;

class CurrencyRepository extends RepositoriesAbstract implements CurrencyInterface
{
    public function getAllCurrencies()
    {
        $data = $this->model
            ->orderBy('order', 'ASC');

        return $this->applyBeforeExecuteQuery($data)->get();
    }
}
