<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Asset as Model;
use App\Http\Resources\Asset as Resource;
use App\Http\Resources\AssetCollection as ResourceCollection;
use App\Http\Requests\StoreAsset as StoreRequest;
use App\Queries\AssetQuery as Query;

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
		if ($user->cant('read', Model::class)) {
			abort(401, 'You are not authorized');
        }

        //Apply proper queries and retrieve a ResourceCollection object.
        $resourceCollection = Query::apply($request);
        return $resourceCollection;
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
    public function store(StoreRequest $request)
    {
        $user = auth()->user();
        if ($user->cant('create', Model::class)) {
            abort(401, 'You are not authorized');
        }
        $object = Model::create($request->all());
        $message = "User {$user->name} has added asset.";
        $object->addLog($message);
        return $object;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $user = auth()->user();
		if ($user->cant('read', Model::class)) {
            abort(401, 'You are not authorized');
        }
        $resourceCollection = Query::apply($request,$id);
        return new Resource($resourceCollection->collection->first());
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
        if ($user->cant('update', Model::class)) {
            abort(401, 'You are not authorized');
        }

		$object = Model::findOrFail($id);
		$object->update($request->all());
		return new Resource($object);
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
        if ($user->cant('delete', Model::class)) {
            abort(401, 'You are not authorized');
        }

		$object = Model::findOrFail($id);
        $message = "User {$user->name} has deleted asset.";
        $object->addLog($message);
		$object->delete();
		return new Resource($object);
    }

}
