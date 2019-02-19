<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App;
use Bouncer;

class addPermission extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'netinv:addPermission {type} {group}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add permissions.  Type = "read" or "write"';

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
        $type = $this->argument('type');
        $group = $this->argument('group');
        $this->assignAdminGroupBouncerRoles($type,$group);
    }

    protected function assignAdminGroupBouncerRoles($type,$group)
    {
        // Assign Network Engineer to Admin.


        echo 'Starting Assigning Permissions to '.$group.PHP_EOL;
        if($type == "write")
        {
            $tasks = [
                'create',
                'read',
                'update',
                'delete',
            ];
        } elseif($type == "read"){
            $tasks = [
                'read',
            ];
        } else {
            print "Invalid TYPE...\n";
            return false;
        }
        
        $types = [
            App\Asset::class,
            App\Part::class,
			App\Partner::class,
			App\Contact::class,
            App\Contract::class,
            App\ServiceNowLocation::class,
            //END-OF-PERMISSION-TYPES
        ];

        foreach ($types as $type) {
            foreach ($tasks as $task) {
                Bouncer::allow($group)->to($task, $type);
            }
        }

        echo 'Finished Assigning Permissions'.PHP_EOL;
    }
}
