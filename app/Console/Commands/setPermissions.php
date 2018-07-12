<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App;
use Bouncer;

class setPermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'netinv:setPermissions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Setup all Bouncer permissions';

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
                $this->assignAdminGroupBouncerRoles(env('GROUP_ADMIN'));
    }

    protected function assignAdminGroupBouncerRoles($group)
    {
        // Assign Network Engineer to Admin.

        echo 'Starting Assigning Permissions to '.$group.PHP_EOL;

        $tasks = [
            'create',
            'read',
            'update',
            'delete',
        ];

        $types = [
            App\Asset::class,
            App\Part::class,
			App\Partner::class,
			App\Contact::class,
			App\Contract::class,
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
