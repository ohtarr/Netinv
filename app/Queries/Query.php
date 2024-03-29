<?php

namespace App\Queries;

use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class Query
{

    public static function parameters(){}
    public static $model;
    public static $resourceCollection;

    public static function apply(Request $request, $id = null)
    {
        $query = QueryBuilder::for(static::$model)
            ->allowedFilters(static::parameters()['filters'])
            ->allowedIncludes(static::parameters()['includes'])
            ->allowedFields(static::parameters()['fields'])
            ->allowedSorts(static::parameters()['sorts'])
            ->defaultSort(static::parameters()['defaultSort']);

            if($id)
            {
                $query->where('id',$id);
            }
        //EXECUTE query and paginate
        $paginator = $query->paginate($request->paginate ?: env('DEFAULT_PAGINATION'))->appends(request()->query());
        //Grab copy of results collection to maintain collection TYPE
        $tmp = $paginator->getCollection();
        //Create a new ResourceCollection object.
        $resourceCollection = new static::$resourceCollection($paginator);
        //Overwrite the resource collection so that it is proper type of Collection Type;
        $resourceCollection->collection = $tmp;
        return $resourceCollection;
    }
}