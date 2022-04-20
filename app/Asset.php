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

	protected $fillable = ['serial','part_id','vendor_id','purchased_at','warranty_id','location_id'];

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

    public function addLog($message, $data = null)
    {
        if(!$data)
        {
            $data = [];
            $lastlog = $this->getLastLog();
            if($lastlog)
            {
                $data = $lastlog->data;
            }
        }
        $log = new Log;
        $log->asset_id = $this->id;
        $log->data = $data;
        $log->message = $message;
        $log->save();
        return $log;
    }

    public function logChanges($data)
    {
        $messages = [];
        $lastlog = $this->getLastLog();
        if($lastlog)
        {
            foreach($data as $key => $value)
            {
                if($lastlog->data[$key] != $value)
                {
                    $messages[] = 'Device ' . $key . ' changed from "' . $lastlog->data[$key] . '" to "' . $value . '".';
                }
            }
        } else {
            foreach($data as $key => $value)
            {
                $messages[] = 'Device ' . $key . ' changed from "" to "' . $value . '".';
            }
        }
        foreach($messages as $message)
        {
            $this->addLog($message, $data);
        }
    }

}