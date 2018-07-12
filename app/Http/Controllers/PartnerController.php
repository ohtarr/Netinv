<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Partner;
use App\Http\Resources\Partner as PartnerResource;
use App\Http\Resources\PartnerCollection;
use App\Http\Requests\StorePartner;
use Validator;

class PartnerController extends Controller
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
                if ($user->cant('read', Partner::class)) {
                        abort(401, 'You are not authorized');
                }

                $params = $request->all();
                if($params)
                {
                        $query = (new Partner)->newQuery();
                        foreach($params as $key => $value)
                        {
                                $query->where($key,$value);
                        }
                        return new PartnerCollection($query->get());
                } else {
                $models = Partner::paginate(1000);
            return new PartnerCollection($models);
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
    public function store(StorePartner $request)
    {
        $user = auth()->user();
        if ($user->cant('create', Partner::class)) {
            abort(401, 'You are not authorized');
        }

                return Partner::create($request->all());
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
                if ($user->cant('read', Partner::class)) {
            abort(401, 'You are not authorized');
        }

        $model = Partner::findOrFail($id);
                return new PartnerResource($model);
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
        if ($user->cant('update', Partner::class)) {
            abort(401, 'You are not authorized');
        }

                $model = Partner::findOrFail($id);
                $model->update($request->all());
                return new PartnerResource($model);
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
        if ($user->cant('delete', Partner::class)) {
            abort(401, 'You are not authorized');
        }

                $model = Partner::findOrFail($id);
                $model->delete();
                return new PartnerResource($model);
    }

        public function filter(Request $request)
        {
                $user = auth()->user();
        if ($user->cant('read', Partner::class)) {
            abort(401, 'You are not authorized');
        }

        }
}