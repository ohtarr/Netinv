<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Collections\PartCollection;

class Part extends Model
{
	use SoftDeletes;

	protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];
	
	protected $fillable = ['type','manufacturer_id','part_number','list_price','current_price','weight'];

    public function newCollection(array $models = []) 
    { 
       return new PartCollection($models); 
    }

    public function assets()
    {
        return $this->hasMany('App\Asset');
    }
	
    public function manufacturer()
    {
        return $this->belongsTo('App\Partner', 'manufacturer_id', 'id');
    }

/*     public function parttype()
    {
        return $this->belongsTo('App\PartType', 'part_type_id', 'id');
    } */
}
