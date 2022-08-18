<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Collections\LogCollection;

class Log extends Model
{
	use SoftDeletes;

    protected $casts = [
        'data'  =>  'json',
    ];

	protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

	protected $guarded = [];

    
    public function newCollection(array $models = []) 
    { 
       return new LogCollection($models); 
    }

	public function asset()
    {
        return $this->belongsTo('App\Asset');
    }

}