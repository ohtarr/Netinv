<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Part;
use App\Http\Resources\Part as PartResource;
use App\Http\Resources\PartCollection;
use App\Http\Requests\StorePart;
use Validator;

class PartController extends Controller
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
		if ($user->cant('read', Part::class)) {
			abort(401, 'You are not authorized');
		}

		$params = $request->all();
		if($params)
		{
			$query = (new Part)->newQuery();
			foreach($params as $key => $value)
			{
				$query->where($key,$value);
			}
			return new PartCollection($query->get());
		} else {
	        $parts = Part::paginate(1000);
    	    return new PartCollection($parts);
		}
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
    public function store(StorePart $request)
    {
        $user = auth()->user();
        if ($user->cant('create', Part::class)) {
            abort(401, 'You are not authorized');
        }

		return Part::create($request->all());
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
		if ($user->cant('read', Part::class)) {
            abort(401, 'You are not authorized');
        }

        $part = Part::findOrFail($id);
		return new PartResource($part);
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
        if ($user->cant('update', Part::class)) {
            abort(401, 'You are not authorized');
        }

		$part = Part::findOrFail($id);
		$part->update($request->all());
		return new PartResource($part);
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
        if ($user->cant('delete', Part::class)) {
            abort(401, 'You are not authorized');
        }

		$part = Part::findOrFail($id);
		$part->delete();
		return new PartResource($part);
    }

	public function filter(Request $request)
	{
		$user = auth()->user();
        if ($user->cant('read', Part::class)) {
            abort(401, 'You are not authorized');
        }

	}
}
