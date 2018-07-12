<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\ServiceNowLocation;

class Contract extends Model
{
	use SoftDeletes;

	protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = ['cid','partner_id','description'];

    public function partner()
    {
        return $this->belongsTo('App\Partner', 'partner_id', 'id');
    }

    public function assets()
    {
        return $this->hasMany('App\Asset', 'id', 'warranty_id');
    }
	
	
}
