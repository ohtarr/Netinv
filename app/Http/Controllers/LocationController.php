<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ServiceNowLocation;
use Validator;
use App\Http\Resources\Location as LocationResource;
use App\Http\Resources\LocationCollection;

class LocationController extends Controller
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
		if ($user->cant('read', ServiceNowLocation::class)) {
			abort(401, 'You are not authorized');
		}

        $locations = ServiceNowLocation::all();
/* 		$locations = QueryBuilder::for(ServiceNowLocation::class)
			->allowedFilters(Filter::exact('sys_id'),Filter::exact('name'))
			->paginate(1000);
 */
        //return $locations;
        return new LocationCollection($locations);
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
		if ($user->cant('read', ServiceNowLocation::class)) {
            abort(401, 'You are not authorized');
        }

        $location = ServiceNowLocation::where("sys_id","=",$id)->first();
		return $location;
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
   
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
       
    }

	public function filter(Request $request)
	{
		
	}
}
