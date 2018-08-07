<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PermissionTableSeeder extends Seeder {

	public function run() {

		if(env('DB_DRIVER') == 'mysql')
			DB::statement('SET FOREIGN_KEY_CHECKS=0;');

		if(env('DB_DRIVER') == 'mysql')
		{
			DB::table(config('access.permissions_table'))->truncate();
			DB::table(config('access.permission_role_table'))->truncate();
			DB::table(config('access.permission_user_table'))->truncate();
		} else { //For PostgreSQL or anything else
			DB::statement("TRUNCATE TABLE ".config('access.permissions_table')." CASCADE");
			DB::statement("TRUNCATE TABLE ".config('access.permission_role_table')." CASCADE");
			DB::statement("TRUNCATE TABLE ".config('access.permission_user_table')." CASCADE");
		}

		$permission_model = config('access.permission');
		$viewBackend = new $permission_model;
		$viewBackend->name = 'view_backend';
		$viewBackend->display_name = 'View Backend';
		$viewBackend->system = true;
		$viewBackend->created_at = Carbon::now();
		$viewBackend->updated_at = Carbon::now();
		$viewBackend->save();

		//Find the first role (admin) give it all permissions
		$role_model = config('access.role');
		$role_model = new $role_model;
		$admin = $role_model::first();
		$admin->permissions()->sync(
			[
				$viewBackend->id,
			]
		);

		$permission_model = config('access.permission');
		$userOnlyPermission = new $permission_model;
		$userOnlyPermission->name = 'user_only_permission';
		$userOnlyPermission->display_name = 'Test User Only Permission';
		$userOnlyPermission->system = false;
		$userOnlyPermission->created_at = Carbon::now();
		$userOnlyPermission->updated_at = Carbon::now();
		$userOnlyPermission->save();

//		foreach(config('access.perm_list') as $k=>$v){
//			$perm_model = new $permission_model;
//			$perm_model->name = $v;
//			$perm_model->display_name = ucwords(str_replace(['-', '_'], ' ', $v));;
//			$perm_model->system = false;
//			$perm_model->created_at = Carbon::now();
//			$perm_model->updated_at = Carbon::now();
//			$perm_model->save();
//		}
		$permission_list = config('access.perm_list');
		$per_created = 0;
		foreach($permission_list as $permission => $role){
			if(!\App\Models\Permission::whereName($permission)->exists()){
				if(preg_match('/^system_/', $permission)){
					$system = true;
				}else{
					$system = false;
				}
				$display_name = preg_replace('/_/', ' ', $permission);
				$new = new \App\Models\Permission();
				$new->unguard();
				$new->fill(['name' => $permission, 'display_name' => $display_name, 'system' => $system]);
				if($new->save()) $per_created++;
				$role_name = config('access.role_list.' . $role, '');
				/** @var Role $role */
				$role = \App\Models\Role::whereName($role_name)->first();
				if($role){
					$new->roles()->attach($role->id);
				}
			}
		}

		echo "\n Created " . $per_created . " permission entries\n";

		$user_model = config('auth.model');
		$user_model = new $user_model;
		$user = $user_model::find(2);
		$user->permissions()->sync(
			[
				$userOnlyPermission->id,
			]
		);

		if(env('DB_DRIVER') == 'mysql')
			DB::statement('SET FOREIGN_KEY_CHECKS=1;');
	}
}