<?php

Route::group(['prefix' => 'access', 'namespace' => 'Access'], function ()
{
	/* User Management */
	Route::resource('users', 'UserController', ['except' => ['show']]);

	Route::get('users/deactivated', ['as' => 'admin.access.users.deactivated', 'uses' => 'UserController@deactivated']);
	Route::get('users/banned', ['as' => 'admin.access.users.banned', 'uses' => 'UserController@banned']);
	Route::get('users/deleted', ['as' => 'admin.access.users.deleted', 'uses' => 'UserController@deleted']);
	Route::get('account/confirm/resend/{user_id}', ['as' => 'admin.account.confirm.resend', 'uses' => 'UserController@resendConfirmationEmail']);

	Route::get('users/becometeacher', ['as' => 'admin.access.users.becometeacher', 'uses' => 'UserController@become_teacher']);
	Route::get('users/becometeacher/{user_id}', [
        'as' => 'admin.access.users.becometeacher.active',
        'uses' => 'UserController@activeTeacher',
    ])->where('user_id', '[0-9]+');
    Route::get('users/becometeacher/remove/{user_id}', [
        'as' => 'admin.access.users.becometeacher.remove',
        'uses' => 'UserController@removeTeacher',
    ])->where('user_id', '[0-9]+');
	Route::get('users/becometeacher/deactive/{user_id}', [
			'as' => 'admin.access.users.becometeacher.deactive',
			'uses' => 'UserController@deActiveTeacher',
	])->where('user_id', '[0-9]+');

	/* Specific User */
	Route::group(['prefix' => 'user/{id}', 'where' => ['id' => '[0-9]+']], function () {
		Route::get('delete', ['as' => 'admin.access.user.delete-permanently', 'uses' => 'UserController@delete']);
		Route::get('restore', ['as' => 'admin.access.user.restore', 'uses' => 'UserController@restore']);
		Route::get('mark/{status}', ['as' => 'admin.access.user.mark', 'uses' => 'UserController@mark'])
			->where([
				'status' => '[0,1,2]'
			]);
		Route::get('password/change', ['as' => 'admin.access.user.change-password', 'uses' => 'UserController@changePassword']);
		Route::post('password/change', ['as' => 'admin.access.user.change-password', 'uses' => 'UserController@updatePassword']);
	});

	/* Roles Management */
	Route::resource('roles', 'RoleController', ['except' => ['show']]);

	/* Permission Management */
	Route::group(['prefix' => 'roles'], function ()
	{
		Route::resource('permissions', 'PermissionController', ['except' => ['show']]);
	});

});