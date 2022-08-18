<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Contract as Model;
use App\Http\Resources\ContractResource as Resource;
use App\Http\Resources\ContractCollection as ResourceCollection;
use App\Http\Requests\StoreContract as StoreRequest;
use App\Queries\ContractQuery as Query;

class ContractController extends Controller
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
		$object->delete();
		return new Resource($object);
    }

}
