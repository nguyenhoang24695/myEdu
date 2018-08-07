<?php

namespace App\Console\Commands\Access;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Console\Command;

class UpdateRolePermission extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'access:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update Role list and permission list.';

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
        //
        $role_list = config('access.role_list');
        $role_created = 0;
        $per_created = 0;
        foreach($role_list as $role => $name){
            if(!Role::whereName($name)->exists()){
                $new = new Role();
                $new->unguard();
                $new->fill(['name' => $name]);
                if($new->save()) $role_created++;
            }
        }

        $permission_list = config('access.perm_list');
        foreach($permission_list as $permission => $role){
            if(!Permission::whereName($permission)->exists()){
                if(preg_match('/^system_/', $permission)){
                    $system = true;
                }else{
                    $system = false;
                }
                $display_name = preg_replace('/_/', ' ', $permission);
                $new = new Permission();
                $new->unguard();
                $new->fill(['name' => $permission, 'display_name' => $display_name, 'system' => $system]);
                if($new->save()) $per_created++;
                $role_name = config('access.role_list.' . $role, '');
                /** @var Role $role */
                $role = Role::whereName($role_name)->first();
                if($role){
                    $new->roles()->attach($role->id);
                }
            }
        }

        echo "Created " . $role_created . " role(s) and " . $per_created . ' permission(s).' . "\n";
    }
}
