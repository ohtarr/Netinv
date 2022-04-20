<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Log;

class populateData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'netinv:populateData';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Go through all logs and populate DATA json field from NAME, LOCATION, and IP in preparation to delete those (3) columns from DB';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
          parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $keys = [
            'name',
            'location',
            'ip',
        ];
        $logs = Log::all();
        foreach($logs as $log)
        {
            if($log->data)
            {
                continue;
            }
            $data = [];
            foreach($keys as $key)
            {
                if(isset($log->$key))
                {
                    $data[$key] = $log->$key;
                }                
            }
            $log->data = $data;
            $log->save();
        }
    }

}