<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Part extends Model
{
	use SoftDeletes;

	protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];
	
	protected $fillable = ['manufacturer_id','part_number','list_price','weight'];

    public function assets()
    {
        return $this->hasMany('App\Asset');
    }
	
    public function manufacturer()
    {
        return $this->belongsTo('App\Partner', 'manufacturer_id', 'id');
    }
}
