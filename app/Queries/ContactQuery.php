<?php

namespace App\Queries;

use App\Queries\Query;
use Spatie\QueryBuilder\Filter;
use App\Contact as Model;
use App\Http\Resources\ContactCollection as ResourceCollection;

class ContactQuery extends Query
{

    public static $model = Model::class;
    public static $resourceCollection = ResourceCollection::class;

    public static function parameters()
    {
        return [
            'filters'       =>  [
                Filter::exact('id'),
    			'name',
			    'email',
			    'phone',
			    'description',
			    Filter::exact('partner_id'),
            ],
            'includes'      =>  [
                'partner',
            ],
            'fields'        =>  [
                'id',
                'name',
                'email',
                'phone',
                'description',
                'partner_id',
            ],
            'sorts'         =>  [
                'id',
                'name',
                'email',
                'phone',
                'description',
                'partner_id',
            ],
            'defaultSort'   =>  'id',
        ];
    }
}