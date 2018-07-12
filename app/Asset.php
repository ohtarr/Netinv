<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\ServiceNowLocation;

class Asset extends Model
{
	use SoftDeletes;

	protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

	protected $fillable = ['serial','part_id','vendor_id','warranty_id','location_id'];

	public function getLocation()
	{
		return ServiceNowLocation::find($this->location_id);
	}

	public function part()
    {
        return $this->belongsTo('App\Part');
    }
	
	public function vendor()
    {
        return $this->belongsTo('App\Partner');
    }
	
	public function warranty()
    {
        return $this->belongsTo('App\Contract','warranty_id','id');
    }	

}