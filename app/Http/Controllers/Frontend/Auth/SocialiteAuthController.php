<?php
/**
 * Created by PhpStorm.
 * User: MyPC
 * Date: 03/06/2016
 * Time: 5:38 CH
 */

namespace App\Http\Controllers\Frontend\Auth;


use App\Http\Controllers\Controller;
use App\Models\User;
use App\Repositories\Backend\Role\RoleRepositoryContract;
use App\Repositories\Frontend\Auth\AuthenticationContract;
use App\Repositories\Frontend\User\UserContract;
use Illuminate\Cookie\CookieJar;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

class SocialiteAuthController extends Controller
{
    protected $auth;
    protected $cookie;
    protected $user;
    public function __construct(AuthenticationContract $auth, CookieJar $cookieJar, UserContract $user)
    {

        $this->auth   = $auth;
        $this->cookie = $cookieJar;
        $this->user   = $user;
    }

    public function handleProviderCallback($provider)
    {
//        dd( Socialite::driver($provider)->stateless()->user());
        try {
            $user = Socialite::driver($provider)->stateless()->user();
        } catch (\Exception $e) {
            return redirect()->route('home');
        }
//        dd($user);
        $authUser = $this->findOrCreateUser($provider,$user);

        \Auth::login($authUser, true);

        $redirect = \Session::get('redirect');
        //Lưu cookie
        $cookie_provider = cookie()->forever(config('access.socialite_session_name'), $provider);
        $this->cookie->queue($cookie_provider);

        if (isset($redirect) && $redirect != "") {
            return \Redirect::to($redirect);
        }

        return redirect()->route('home');
    }

    private function findOrCreateUser($provider,$data)
    {
        $social_column  =  "";
        switch ($provider) {
            case 'facebook':
                $social_column = "facebook_id";
                break;

            case 'google':
                $social_column = "google_id";
                break;

            case 'github':
                $social_column = "github_id";
                break;

            case 'twitter':
                $social_column = "twitter_id";
                break;

            case 'linkedin':
                $social_column = "linkedin_id";
                break;

            case 'bitbucket':
                $social_column = "bitbucket_id";
                break;
        }

        if ($social_column != "") {

            $authUser = User::where('email', $data->email)->first();
            if ($authUser){
                //cập nhật id facebook,google
                if($authUser->$social_column == ""){
                    $authUser->update([$social_column => $data->id]);
                }

                if($authUser->confirmation_code == ""){
                    $authUser->update(['confirmation_code' => md5(uniqid(mt_rand(), true))]);
                }
                return $authUser;
            }

            $user = $this->user->create($data,true,true);
            if($user){
                $user->update([$social_column => $data->id]);
            }
            return $user;
        }
    }
}