<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Asset;
use App\Http\Resources\Asset as AssetResource;
use App\Http\Resources\AssetCollection;
use App\Http\Requests\StoreAsset;
use Validator;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\Filter;

class AssetController extends Controller
{

	public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
		$user = auth()->user();
		if ($user->cant('read', Asset::class)) {
			abort(401, 'You are not authorized');
        }
        
        if($request->paginate)
        {
            $paginate = $request->paginate;
        } else {
            $paginate = env("ASSETS_PAGINATION");
        }

		$assets = QueryBuilder::for(Asset::class)
			->allowedFilters(Filter::exact('id'),Filter::exact('serial'),Filter::exact('part_id'),Filter::exact('vendor_id'),Filter::exact('warranty_id'),Filter::exact('location_id'))
			->allowedIncludes('part','vendor','warranty')
			->paginate($paginate);
			
		//$assets = Asset::paginate(1000);
		//return new AssetCollection($assets);
		/*
		$params = $request->all();
		if($params)
		{
			$query = (new Asset)->newQuery();
			foreach($params as $key => $value)
			{
				$query->where($key,$value);
			}
			return new AssetCollection($query->get());
		} else {
	        $assets = Asset::paginate(1000);
    	    return new AssetCollection($assets);
		}
		/**/
		return new AssetCollection($assets);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreAsset $request)
    {
        $user = auth()->user();
        if ($user->cant('create', Asset::class)) {
            abort(401, 'You are not authorized');
        }

		return Asset::create($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = auth()->user();
		if ($user->cant('read', Asset::class)) {
            abort(401, 'You are not authorized');
        }

        $asset = Asset::findOrFail($id);
		return new AssetResource($asset);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = auth()->user();
        if ($user->cant('update', Asset::class)) {
            abort(401, 'You are not authorized');
        }

		$asset = Asset::findOrFail($id);
		$asset->update($request->all());
		return new AssetResource($asset);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = auth()->user();
        if ($user->cant('delete', Asset::class)) {
            abort(401, 'You are not authorized');
        }

		$asset = Asset::findOrFail($id);
		$asset->delete();
		return new AssetResource($asset);
    }

	public function filter(Request $request)
	{
		$user = auth()->user();
        if ($user->cant('read', Asset::class)) {
            abort(401, 'You are not authorized');
        }

	}
}
