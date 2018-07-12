<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Contact;
use App\Http\Resources\Contact as ContactResource;
use App\Http\Resources\ContactCollection;
use App\Http\Requests\StoreContact;
use Validator;

class ContactController extends Controller
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
                if ($user->cant('read', Contact::class)) {
                        abort(401, 'You are not authorized');
                }

                $params = $request->all();
                if($params)
                {
                        $query = (new Contact)->newQuery();
                        foreach($params as $key => $value)
                        {
                                $query->where($key,$value);
                        }
                        return new ContactCollection($query->get());
                } else {
                $models = Contact::paginate(1000);
            return new ContactCollection($models);
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
    public function store(StoreContact $request)
    {
        $user = auth()->user();
        if ($user->cant('create', Contact::class)) {
            abort(401, 'You are not authorized');
        }

                return Contact::create($request->all());
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
                if ($user->cant('read', Contact::class)) {
            abort(401, 'You are not authorized');
        }

        $model = Contact::findOrFail($id);
                return new ContactResource($model);
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
        if ($user->cant('update', Contact::class)) {
            abort(401, 'You are not authorized');
        }

                $model = Contact::findOrFail($id);
                $model->update($request->all());
                return new ContactResource($model);
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
        if ($user->cant('delete', Contact::class)) {
            abort(401, 'You are not authorized');
        }

                $model = Contact::findOrFail($id);
                $model->delete();
                return new ContactResource($model);
    }

        public function filter(Request $request)
        {
                $user = auth()->user();
        if ($user->cant('read', Contact::class)) {
            abort(401, 'You are not authorized');
        }

        }
}