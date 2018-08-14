<?php

/**
 * Frontend Access Controllers
 */
Route::group(['namespace' => 'Auth'], function ()
{
	Route::group(['middleware' => 'auth'], function ()
	{
		Route::get('auth/logout', 'AuthController@getLogout');//
		Route::get('auth/password/change', 'PasswordController@getChangePassword');
		Route::post('auth/password/change', ['as' => 'password.change', 'uses' => 'PasswordController@postChangePassword']);
	});

	Route::group(['middleware' => 'guest'], function ()
	{
		Route::get('auth/login/{provider}', ['as' => 'auth.provider', 'uses' => 'AuthController@loginThirdParty']);
		Route::get('account/confirm/{token}', ['as' => 'account.confirm', 'uses' => 'AuthController@confirmAccount']);
		Route::get('account/confirm/resend/{user_id}', ['as' => 'account.confirm.resend', 'uses' => 'AuthController@resendConfirmationEmail']);

		Route::controller('auth', 'AuthController');
		Route::controller('password', 'PasswordController');
	});

	/**
	 ** Đăng nhập qua facebook, google
	 **/
	Route::group(['prefix' => 'app'], function () {
		//Xử lý call back sau khi login qua facebook

		Route::get('{provider}/callback', 'SocialiteAuthController@handleProviderCallback');
	});

	Route::group(['prefix' => 'idvg'], function () {
		Route::get('return', [
		    'as' 	=> 'idvg.return',
		    'uses'  => 'IdvgController@store'
		]);
		Route::post('change', [
				'as' 		 => 'idvg.change',
				'uses'  	 => 'IdvgController@update'
		]);
		Route::get('change', [
				'as' 		 => 'idvg.change',
				'uses'  	 => 'IdvgController@update'
		]);
		Route::get('login/{uri}', [
				'as' 	=> 'idvg.login',
				'uses'  => 'IdvgController@login'
		]);
		Route::get('register/{uri}', [
				'as' 	=> 'idvg.register',
				'uses'  => 'IdvgController@register'
		]);
		Route::get('logout/{uri}', [
				'as' 	=> 'idvg.logout',
				'uses'  => 'IdvgController@logout'
		]);
		Route::get('logout', [
				'as' 	=> 'idvg.donelogout',
				'uses'  => 'IdvgController@donelogout'
		]);
		Route::get('setting/{uri}', [
				'as' 	=> 'idvg.setting',
				'uses'  => 'IdvgController@setting'
		]);
	});

});

