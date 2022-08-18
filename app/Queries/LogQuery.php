<?php

namespace App\Queries;

use App\Queries\Query;
use Spatie\QueryBuilder\Filter;
use App\Log as Model;
use App\Http\Resources\LogResourceCollection as ResourceCollection;

class LogQuery extends Query
{

    public static $model = Model::class;
    public static $resourceCollection = ResourceCollection::class;

    public static function parameters()
    {
        return [
            'filters'       =>  [
                Filter::exact('asset_id'),
                Filter::exact('asset.id'),
                'message',
                Filter::exact('asset.serial'), 
            ],
            'includes'      =>  [
                'asset',
                'asset.part',
                'asset.vendor',
            ],
            'fields'        =>  [
                'id',
                'asset_id',
                'ip',
                'name',
                'location',
                'message',
                'created_at',
                'updated_at',
                'deleted_at',
                'data',
                'asset.id',
                'asset.serial',
                'asset.location_id',
            ],
            'sorts'         =>  [
                'id',
                'asset_id',
                'message'
            ],
            'defaultSort'   =>  'id',
        ];
    }
}