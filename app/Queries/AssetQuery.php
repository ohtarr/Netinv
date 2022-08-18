<?php

namespace App\Queries;

use App\Queries\Query;
use Spatie\QueryBuilder\Filter;
use App\Asset as Model;
use App\Http\Resources\AssetCollection as ResourceCollection;

class AssetQuery extends Query
{

    public static $model = Model::class;
    public static $resourceCollection = ResourceCollection::class;

    public static function parameters()
    {
        return [
            'filters'       =>  [
                Filter::exact('id'),
                Filter::exact('part_id'),
                'serial',
                Filter::exact('location_id'),
                Filter::exact('vendor_id'),
                Filter::exact('warranty_id'), 
            ],
            'includes'      =>  [
                'part',
                'vendor',
                'warranty',
                'logs',
            ],
            'fields'        =>  [
                "id",
				"serial",
				"part_id",
				"vendor_id",
				"purchased_at",
				"warranty_id",
				"location_id",
				"created_at",
				"updated_at",
				"deleted_at",
				"last_online",
            ],
            'sorts'         =>  [
                "id",
				"serial",
				"part_id",
				"vendor_id",
				"purchased_at",
				"warranty_id",
				"location_id",
				"created_at",
				"updated_at",
				"deleted_at",
				"last_online",
            ],
            'defaultSort'   =>  'id',
        ];
    }
}