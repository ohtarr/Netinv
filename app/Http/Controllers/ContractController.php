<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Contract;
use App\Http\Resources\Contract as ContractResource;
use App\Http\Resources\ContractCollection;
use App\Http\Requests\StoreContract;
use Validator;

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
                if ($user->cant('read', Contract::class)) {
                        abort(401, 'You are not authorized');
                }

                $params = $request->all();
                if($params)
                {
                        $query = (new Contract)->newQuery();
                        foreach($params as $key => $value)
                        {
                                $query->where($key,$value);
                        }
                        return new ContractCollection($query->get());
                } else {
                $models = Contract::paginate(1000);
            return new ContractCollection($models);
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
    public function store(StoreContract $request)
    {
        $user = auth()->user();
        if ($user->cant('create', Contract::class)) {
            abort(401, 'You are not authorized');
        }

                return Contract::create($request->all());
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
                if ($user->cant('read', Contract::class)) {
            abort(401, 'You are not authorized');
        }

        $model = Contract::findOrFail($id);
                return new ContractResource($model);
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
        if ($user->cant('update', Contract::class)) {
            abort(401, 'You are not authorized');
        }

                $model = Contract::findOrFail($id);
                $model->update($request->all());
                return new ContractResource($model);
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
        if ($user->cant('delete', Contract::class)) {
            abort(401, 'You are not authorized');
        }

                $model = Contract::findOrFail($id);
                $model->delete();
                return new ContractResource($model);
    }

        public function filter(Request $request)
        {
                $user = auth()->user();
        if ($user->cant('read', Contract::class)) {
            abort(401, 'You are not authorized');
        }

        }
}