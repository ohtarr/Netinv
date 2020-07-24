<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Asset;
use App\Http\Resources\Asset as AssetResource;
use App\Http\Resources\AssetCollection;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\Filter;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->paginate)
        {
            $paginate = $request->paginate;
        } else {
            $paginate = env("ASSETS_PAGINATION");
        }

        $filters = [
            'id',
            'serial',
            'part_id',
            'vendor_id',
            'warranty_id',
            'location_id',
        ];

        $includes = [
            'part',
            'part.manufacturer',
            'vendor',
            'warranty',
        ];

		$query = QueryBuilder::for(Asset::class);
		$query->allowedFilters($filters);
		$query->allowedIncludes($includes);

        if ($request->get('type')) {
            $query->join('parts', 'parts.id', '=', 'assets.part_id');
            $query->where('parts.type', $request->get('type'));
        }

        if ($request->get('manufacturer_id')) {
            $query->join('parts', 'parts.id', '=', 'assets.part_id');
            $query->where('parts.manufacturer_id', $request->get('manufacturer_id'));
        }

        $assets = $query->paginate($paginate);

        return new AssetCollection($assets);
    }
}
