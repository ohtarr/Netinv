<?php

namespace App\Queries;

use App\Queries\Query;
use Spatie\QueryBuilder\Filter;
use App\Part as Model;
use App\Http\Resources\PartCollection as ResourceCollection;

class PartQuery extends Query
{

    public static $model = Model::class;
    public static $resourceCollection = ResourceCollection::class;

    public static function parameters()
    {
        return [
            'filters'       =>  [
                Filter::exact("id"),
    			Filter::exact("manufacturer_id"),
	    		"part_number",
		    	"list_price",
			    Filter::exact("weight"),
			    "type",
            ],
            'includes'      =>  [
                'assets',
                'assets.logs',
                'manufacturer',
            ],
            'fields'        =>  [
                "id",
			    "manufacturer_id",
			    "part_number",
			    "list_price",
			    "weight",
    			"created_at",
			    "updated_at",
			    "deleted_at",
			    "type",
			    "current_price",
            ],
            'sorts'         =>  [
                'id',
                'manufacturer_id',
                'part_number',
                'list_price',
                'weight',
                'created_at',
                'updated_at',
                'deleted_at',
                'type',
                'current_price',
            ],
            'defaultSort'   =>  'id',
        ];
    }
}