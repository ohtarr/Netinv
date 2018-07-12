<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\ServiceNowLocation;

class Contact extends Model
{
	use SoftDeletes;

	protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = ['name','email','phone','description','partner_id'];
	
	public function partner()
    {
        return $this->belongsTo('App\Partner');
    }
	
}
