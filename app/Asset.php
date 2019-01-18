<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\ServiceNowLocation;
use App\Log;

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
    public function logs()
    {
        return $this->hasMany('App\Log');
    }

    public function getLastLog()
    {
        $this->refresh();
        return $this->logs->last();
    }

    public function logChanges($newname, $newip, $newloc)
    {
        $messages = [];
        $lastlog = $this->getLastLog();
        //print_r($lastlog);
        if($lastlog)
        {
            if($lastlog->name != $newname)
            {
                $messages[] = 'Device NAME changed from "' . $lastlog->name . '" to "' . $newname . '".';
            }
            if($lastlog->ip != $newip)
            {
                $messages[] = 'Device IP changed from "' . $lastlog->ip . '" to "' . $newip . '".';
            }
            if($lastlog->location != $newloc)
            {
                $messages[] = 'Device LOCATION changed from "' . $lastlog->location . '" to "' . $newloc . '".';
            }
        } else {
            $messages[] = 'Device NAME changed from "" to "' . $newname . '".';
            $messages[] = 'Device IP changed from "" to "' . $newip . '".';
            $messages[] = 'Device LOCATION changed from "" to "' . $newloc . '".';
        }
        //print_r($messages);
        foreach($messages as $message)
        {
            $log = new Log;
            $log->asset_id = $this->id;
            $log->name = $newname;
            $log->ip = $newip;
            $log->location = $newloc;
            $log->message = $message;
            $log->save();
        }
    }

}