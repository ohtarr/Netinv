<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\ServiceNowLocation;
use App\Collections\PartnerCollection;

class Partner extends Model
{
	use SoftDeletes;

	protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];
	
	protected $fillable = ['name','description','url','discount'];
	
    public function newCollection(array $models = []) 
    { 
       return new PartnerCollection($models); 
    }

    public function assets()
    {
        return $this->hasMany('App\Asset','vendor_id','id');
    }
	
    public function parts()
    {
        return $this->hasMany('App\Part', 'manufacturer_id','id');
    }

    public function contacts()
    {
        return $this->hasMany('App\Contact');
    }
	
}
