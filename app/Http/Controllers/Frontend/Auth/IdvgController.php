<?php

namespace App\Http\Controllers\Frontend\Auth;

use App\Core\GetConfigIdvg;
use App\Core\HttpAuthData;
use App\Core\Idvg\SignInToken;
use App\Core\Idvg\SSOHelper;
use App\Core\MyIndexer;
use App\Exceptions\GeneralException;
use App\Models\User;
use App\Repositories\Backend\Role\RoleRepositoryContract;
use App\Repositories\Frontend\Auth\AuthenticationContract;
use App\Repositories\Frontend\User\UserContract;
use Illuminate\Cookie\CookieJar;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use GuzzleHttp;
use Illuminate\Support\Facades\Log;

class IdvgController extends Controller
{
    protected $conf;
    protected $auth;
    protected $user;
    protected $role;
    protected $authData;
    protected $cookie;

    public function __construct(GetConfigIdvg $conf_idvg,
                                AuthenticationContract $auth,
                                UserContract $user,
                                RoleRepositoryContract $role,
                                HttpAuthData $authData,
                                CookieJar $cookieJar
                                )
    {
        $this->conf     = $conf_idvg;
        $this->auth     = $auth;
        $this->user     = $user;
        $this->role     = $role;
        $this->authData = $authData;
        $this->cookie   = $cookieJar;
    }

    /**
     * Create Url from unibee
     *
     * @return url redirect
     */
    public function login($uri)
    {
        $url_call_back      =   $this->conf->getUrlCallBack($uri);
        if(config('idvg.lock_sso')){
            $url_idvg_login =   $this->conf->getUrlLoginSSO($url_call_back);
        } else {
            $url_idvg_login =   $this->conf->getUrlLogin($url_call_back);
        }
        return redirect($url_idvg_login);
    }

    /**
     * Create Url from unibee
     *
     * @return url redirect
     */
    public function register($uri)
    {
        $url_call_back             =   $this->conf->getUrlCallBack($uri);
        if(config('idvg.lock_sso')){
            $url_idvg_register     =   $this->conf->getUrlRegisterSSO($url_call_back);
        } else {
            $url_idvg_register     =   $this->conf->getUrlRegister($url_call_back);
        }
        return redirect($url_idvg_register);
    }

    public function logout($uri)
    {
        $url_call_back         =   $this->conf->getUrlCallBackLogout($uri);
        if(config('idvg.lock_sso')){
            $url_idvg_logout       =  $this->conf->getUrlLogoutSSO($url_call_back);
        } else {
            $url_idvg_logout       =   $this->conf->getUrlLogout($url_call_back);
        }

        return redirect($url_idvg_logout);
    }

    public function setting($uri)
    {
        $url_idvg_setting   =   $this->conf->getUrlSetting(base64_decode($uri));
        return redirect($url_idvg_setting);
    }

    public function donelogout(Request $request)
    {
        $url_redirect          =   base64_decode($request->get('uri'));
        $this->auth->logoutWithIdvg();
        return redirect($url_redirect);
    }

    /**
     * Store info from idvg
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $url_redirect       =   base64_decode($request->get('uri'));
        if(config('idvg.lock_sso')){

            //Qua SSO
            $token           =   $request->get('token');
            if($token != ""){
                $data_login  =   $this->signInByToken($token);
            } else {
                return redirect('/');
            }

        } else {

            //Đăng nhập qua authen
            $access_code     =   $request->get('access_code');
            if($access_code != ""){
                $data_login  =   $this->getInfoLogin($access_code);
             } else {
                return redirect('/');
            }
        }

        foreach($data_login as $key=>$value){

            //Kiểm tra xem user đã có trên unibee hay chưa
            $user = User::where('email', $value->acc->email)->first();
            if(! $user) {
                $user = $this->syncIdvg([
                        'name'          => $value->acc->first_name .' '. $value->acc->last_name,
                        'full_name'     => $value->acc->first_name .' '. $value->acc->last_name,
                        'email'         => $value->acc->email,
                        'gender'        => ($value->acc->gender == 'MALE') ? 0:1,
                        'address'       => ($value->acc->address != '') ? $value->acc->address : '',
                        'birthday'      => ($value->acc->dob != '') ? $value->acc->dob : '',
                        'phone'         => ($value->acc->phone != '') ? $value->acc->phone : '',
                        'idvg_id'       => ($value->acc->id != '') ? $value->acc->id : '',
                        'facebook_id'   => ($value->acc->facebook_id != '') ? $value->acc->facebook_id : ''
                ]);
            }

            try {

                $this->auth->loginWithIdvg($user);
                SSOHelper::saveGSN($value->gsn, $value->expired_time);
                //Lưu cookie
                $cookie_provider = cookie()->forever(config('access.socialite_session_name'), 'idvg');
                $this->cookie->queue($cookie_provider);

                return redirect()->intended($url_redirect);

            } catch (GeneralException $e) {

                return redirect($url_redirect)->withFlashDanger($e->getMessage());
            }
        }

    }

    /**
     * Update info from idvg change
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $data      = $request->get('data');
        $event     = $request->get('name');
        $validUser = $this->conf->getAuthUnibeeName();
        $validPass = $this->conf->getAuthUnibeePass();
        $this->authData->checkAuthDigest($validUser,$validPass);
        try {
            if ($event != "" && in_array(strtoupper($event), $this->conf->getEventIdvg())) {
                $data = json_decode($data);
                switch ($event) {
                    case "ON_USERINFO_CHANGED":
                        if (isset($data->email) && $data->email != '') {
                            $user = User::where('email', $data->email)->first();
                            if ($user->id > 0) {
                                $user->update([
                                    'full_name' => $data->first_name . ' ' . $data->last_name,
                                    'birthday'  => $data->dob,
                                    'gender'    => ($data->gender == 'MALE') ? 0 : 1,
                                    'address'   => $data->address
                                ]);
                            }
                        }
                        break;
                    case "ON_USEREMAIL_CHANGED":
                        if (isset($data->old_email) && $data->old_email != '') {
                            if (isset($data->new_email)
                                && $data->new_email != ''
                                && $data->old_email != $data->new_email
                            ) {
                                $user = User::where('email', $data->old_email)->first();
                                if ($user->id > 0) {
                                    $user->update([
                                        'email' => $data->new_email
                                    ]);
                                }
                            }
                        }
                        break;
                    case "ON_USERPHONE_CHANGED":
                        if (isset($data->old_phone) && $data->old_phone != '') {
                            if (isset($data->new_phone)
                                && $data->new_phone != ''
                                && $data->old_phone != $data->new_phone
                            ) {
                                $user = User::where('phone', $data->old_phone)->first();
                                if ($user->id > 0) {
                                    $user->update([
                                        'phone' => $data->new_phone
                                    ]);
                                }
                            }
                        }
                        break;
                    case "ON_USERNAME_CHANGED":
                        break;

                }

                //Trả về thành công cho bên idvg là đã lấy dữ liệu thành công
                return response()->json([
                    'error' => false,
                    'message' => 'ok',
                    'code' => 200],
                    200
                );
            }
        }catch (\Exception $ex){
            // do nothing
        }

    }

    /*
    * Get info login from IDVG
    * @return json $data
    */
    public function getInfoLogin($access_code){
        $url        = 'https://id.vatgia.com/oauth2/accessCode/'.$access_code.'?with=acc';
        $client     = new GuzzleHttp\Client();
        $response   = $client->request('GET', $url, [
            'auth' => [$this->conf->getAuthIdvgName(), $this->conf->getAuthIdvgPass()],
            'verify' => false
        ]);

        if($response->getStatusCode() == 200){
            $result = json_decode($response->getBody());
            $data   = $result->objects;
            return $data;
        } else {
            return redirect('/')->withFlashDanger($response->getStatusCode()." Lỗi hệ thống IDVG");
        }
    }

    /**
     * @param $data
     * @param bool $provider
     * @return static
     */
    public function syncIdvg($data){
        $user = User::create([
            'name' 		    => $data['name'],
            'full_name'     => $data['full_name'],
            'email' 	    => $data['email'],
            'gender'        => $data['gender'],
            'address'       => $data['address'],
            'birthday'      => $data['birthday'],
            'phone'         => $data['phone'],
            'idvg_id'       => $data['idvg_id'],
            'facebook_id'   => $data['facebook_id'],
            'status'        => 1,
            'confirmed'     => 1,
            'password'      => null,
            'confirmation_code' => md5(uniqid(mt_rand(), true))
        ]);
        //index dữ liệu sang elatic search
        MyIndexer::indexUser($user);

        $roles = $this->role->getDefaultUserRole();
        foreach($roles as $role){
            $user->attachRole($role);
        }
        return $user;
    }

    /**************************************/
    /******ĐĂNG NHẬP IDVG THEO CÁCH SS0*******/
    /**************************************/


    /**
     * Hàm này đăng nhập người dùng dựa trên 1 SignInToken
     *
     * @param string $token SignInToken được truyền sang từ id.vatgia.com
     */
    function signInByToken($token)
    {
        $secretKey = $this->conf->getSecretKey();
        $publicKey = $this->conf->getPublickey();
        $s = new SignInToken(array(), $secretKey, $publicKey);
        if (SSOHelper::isRefererValid() && $s->decrypt($token) == SignInToken::ERROR_NONE) {
            // Header này chỉ định dành cho IE, để cho phép cross domain cookie
            header('P3P: CP="CAO PSA OUR"');
            $data = $s->getData(); // dữ liệu của SignInToken

            $arr_data['acc']           = $data['info'];
            $arr_data['sign_in_time']  = $data['sign_in_time'];
            $arr_data['expired_time']  = $data['expired_time'];
            $arr_data['gsn']           = $data['gsn'];
            return json_decode(json_encode([$arr_data]));
        }
    }

}
