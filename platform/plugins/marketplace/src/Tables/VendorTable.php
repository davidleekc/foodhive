<?php

namespace Botble\Marketplace\Tables;

use Botble\Ecommerce\Tables\CustomerTable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Query\Builder as QueryBuilder;

class VendorTable extends CustomerTable
{
    public function query(): Relation|Builder|QueryBuilder
    {
        $query = $this->repository->getModel()
            ->select([
                'id',
                'name',
                'email',
                'avatar',
                'created_at',
                'status',
                'confirmed_at',
            ])
            ->where('is_vendor', true);

        return $this->applyScopes($query);
    }
}
