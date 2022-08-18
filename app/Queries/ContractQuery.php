<?php

namespace App\Queries;

use App\Queries\Query;
use Spatie\QueryBuilder\Filter;
use App\Contract as Model;
use App\Http\Resources\ContractCollection as ResourceCollection;

class ContractQuery extends Query
{

    public static $model = Model::class;
    public static $resourceCollection = ResourceCollection::class;

    public static function parameters()
    {
        return [
            'filters'       =>  [
                Filter::exact('id'),
                Filter::exact('partner_id'),
                Filter::exact('cid'),
                'description',
            ],
            'includes'      =>  [
                'partner',
                'assets',
            ],
            'fields'        =>  [
                "id",
			    "cid",
			    "partner_id",
			    "description",
			    "created_at",
			    "updated_at",
			    "deleted_at",
            ],
            'sorts'         =>  [
                "id",
			    "cid",
			    "partner_id",
			    "description",
            ],
            'defaultSort'   =>  'id',
        ];
    }
}