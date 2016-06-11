<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Permission\Models\Permission;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class PermissionsCommand extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'permissions:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add required permissions for the app.';

    // List of permissions
    protected $permissions = [
        'role-admin',
    ];

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        foreach($this->permissions AS $permissionName) {
            $permission = Permission::find($permissionName);
            if (!$permission) {
                Permission::create(['name' => $permissionName]);
            }
        }
    }

}
