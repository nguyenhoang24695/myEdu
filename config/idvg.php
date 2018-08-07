<?php 

return [

	//Tùy chình ON/OFF login theo SSO hay bình thường
	//Mặc định là bật SSO để login
	'lock_sso'		=> true,

	/*
	|--------------------------------------------------------------------------
	| Thông tin đăng ký tích hợp với bên IDVG 
	|--------------------------------------------------------------------------
	| 
	*/

	'client_id' 	=>  env('IDVG_CLIENT_ID'),
	'service'		=>  env('IDVG_SERVICE'),
	'domain'		=>  env('DOMAIN'),
	'mode'			=> 'popup',
	'http_user'		=>  env('IDVG_HTTP_USER'),
	'http_pass'		=>	env('IDVG_HTTP_PASS'),

	/*
	|--------------------------------------------------------------------------
	| Url Api bên IDVG cung cấp để thực hiện
	|--------------------------------------------------------------------------
	| 
	*/

	'url_login'				=> 'https://id.vatgia.com/dang-nhap/oauth?',
	'url_login_facebook'	=> 'https://id.vatgia.com/dang-nhap/facebook?',
	'url_login_google'		=> 'https://id.vatgia.com/dang-nhap/google?',
	'url_register'			=> 'https://id.vatgia.com/dang-ky/oauth?',
	'url_logout'			=> 'https://id.vatgia.com/dang-xuat/?',
	'url_seting'			=> 'https://id.vatgia.com/thiet-lap/thong-tin?',

	/*
	|--------------------------------------------------------------------------
	| Url unibee nhận dữ liệu bên idvg trả về
	|--------------------------------------------------------------------------
	| 
	*/

	'unibee'	=> [
		'url_login_return' => 'http://'.env('DOMAIN').'/idvg/return',
		'url_event'  	   => 'http://'.env('DOMAIN').'/idvg/change',
		'url_logout'	   => 'http://'.env('DOMAIN').'/idvg/logout',
		'http_user'		   => env('HTTP_UNIBEE_NAME'),
		'http_pass'		   => env('HTTP_UNIBEE_PASS')
	],

	/*
	|--------------------------------------------------------------------------
	| Thông tin kết nối
	|--------------------------------------------------------------------------
	| 
	*/

	'connections'	=> [
		'secretKey'			=> env('SECRET_KEY'),
		'publicKey'			=> ''
	],

	'sso'	=> [
		'url_login'		=> 'https://id.vatgia.com/dang-nhap?',
		'url_logout'	=> 'https://id.vatgia.com/dang-xuat?',
		'url_register'	=> 'https://id.vatgia.com/dang-ky?',
	],


	/*
	|--------------------------------------------------------------------------
	| Mảng chứa các event bên IDVG quy định 
	|--------------------------------------------------------------------------
	| Một số event IDVG quy định để lắng nghe bên Unibee.org yêu cầu
	| ON_USER_CREATED => Tạo mới tài khoản
	| ON_USERINFO_CHANGED => Chỉnh sửa thông tin cá nhân
	| ON_USEREMAIL_CHANGED => Chỉnh sửa Email
	| ON_USERPHONE_CHANGED => Chỉnh sửa số điện thoại
	| ON_USERNAME_CHANGED => Chỉnh sửa tên đăng nhập
	*/

	'event'			=> [
		'ON_USERINFO_CHANGED',
		'ON_USEREMAIL_CHANGED',
		'ON_USERPHONE_CHANGED',
		'ON_USERNAME_CHANGED'
	]
];

?>