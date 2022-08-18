<?php

namespace App\Queries;

use App\Queries\Query;
use Spatie\QueryBuilder\Filter;
use App\Partner as Model;
use App\Http\Resources\PartnerCollection as ResourceCollection;

class PartnerQuery extends Query
{

    public static $model = Model::class;
    public static $resourceCollection = ResourceCollection::class;

    public static function parameters()
    {
        return [
            'filters'       =>  [
                Filter::exact("id"),
                "name",
                "url",
                "description",
                Filter::exact("discount"),
            ],
            'includes'      =>  [
                'assets',
                'parts',
                'contacts',
            ],
            'fields'        =>  [
                "id",
                "name",
                "url",
                "description",
                "created_at",
                "updated_at",
                "deleted_at",
                "discount",
            ],
            'sorts'         =>  [
                "id",
                "name",
                "url",
                "description",
                "created_at",
                "updated_at",
                "deleted_at",
                "discount",
            ],
            'defaultSort'   =>  'id',
        ];
    }
}