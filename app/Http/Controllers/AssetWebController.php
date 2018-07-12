<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Asset as Asset;
use App\Http\Resources\Asset as AssetResource;

class AssetWebController extends Controller
{

    public function __construct()
    {
        $this->middleware('web');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
		$user = auth()->user();
		$assets = Asset::all();

		// load the view and pass the nerds
		return view('assets2.index')
			->with('assets', $assets);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = auth()->user();
		return view('assets2.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = auth()->user();
		request()->validate([
			'serial'		=>	'unique:assets',
			'manufacturer'	=>	'required',
			'model'			=>	'required',
			'vendor_name'	=>	'required',
			'location_id'	=>	'required',
		]);
		Asset::create($request->all());
		return redirect()->route('assets.index')
			->with('success','Asset created successfully');
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
		$asset = Asset::find($id);
		return view('assets.show',compact('asset'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = auth()->user();
		$asset = Asset::find($id);
		return view('assets2.edit',compact('asset','id'));
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
		request()->validate([
			'serial'		=>	'unique:assets',
			'manufacturer'	=>	'required',
			'model'			=>	'required',
			'vendor_name'	=>	'required',
			'location_id'	=>	'required',
		]);
		Asset::find($id)->update($request->all());
		return redirect()->route('assets.index')
			->with('success','Asset updated successfully');
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
		Asset::find($id)->delete();
		return redirect()->route('assets.index')
			->with('success','Asset deleted successfully');
    }
}
