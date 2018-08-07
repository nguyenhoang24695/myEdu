<?php namespace App\Http\Controllers\Frontend\Auth;

use App\Models\Course;
use Illuminate\Cookie\CookieJar;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use App\Repositories\Frontend\Auth\AuthenticationContract;
use App\Http\Requests\Frontend\Access\LoginRequest;
use App\Http\Requests\Frontend\Access\RegisterRequest;
use App\Exceptions\GeneralException;

/**
 * Class AuthController
 * @package App\Http\Controllers\Frontend\Auth
 */
class AuthController extends Controller
{

    use ThrottlesLogins;

    /**
     * @param AuthenticationContract $auth
     */
    public function __construct(AuthenticationContract $auth)
    {
        $this->auth = $auth;
    }

    /**
     * @return \Illuminate\View\View
     */
    public function getRegister()
    {
        return view('frontend.auth.register');
    }

    /**
     * @param RegisterRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postRegister(RegisterRequest $request)
    {
        if (config('access.users.confirm_email')) {
            var_dump($request->all());


            $this->auth->create($request->all());


            return redirect()->route('home')->withFlashSuccess(trans('auth.register_success'));
        } else {
            //Use native auth login because do not need to check status when registering
            auth()->login($this->auth->create($request->all()));
            return redirect()->route('frontend.dashboard');
        }
    }

    /**
     * @return \Illuminate\View\View
     */
    public function getLogin()
    {
        \Session::put('redirect', \URL::previous());
        return view('frontend.auth.login');
    }

    /**
     * Hàm này sẽ lưu lại thông tin trước khi chuyển tiếp sang trang đăng nhập
     *
     * Xử lý khi ấn nút đăng ký khóa học những chưa đăng nhập
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function postLogin1(Request $request)
    {

        $course_id = $request->get('course_id');
        \Session::put('click_from_course_register_link', $course_id);

        return $this->getLogin();
    }

    /**
     * @param LoginRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postLogin(LoginRequest $request)
    {
        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        $throttles = $this->isUsingThrottlesLoginsTrait();

        if ($throttles && $this->hasTooManyLoginAttempts($request)) {
            return $this->sendLockoutResponse($request);
        }

        //Don't know why the exception handler is not catching this
        try {
            $this->auth->login($request);

            if ($throttles) {
                $this->clearLoginAttempts($request);
            }

            $redirect = \Session::get('redirect');
            if (isset($redirect) && $redirect != "") {
                return \Redirect::to($redirect);
            }

            return redirect()->route('home');

        } catch (GeneralException $e) {
            // If the login attempt was unsuccessful we will increment the number of attempts
            // to login and redirect the user back to the login form. Of course, when this
            // user surpasses their maximum number of attempts they will get locked out.
            if ($throttles) {
                $this->incrementLoginAttempts($request);
            }

            return redirect()->back()->withInput()->withFlashDanger($e->getMessage());
        }
    }

    /**
     * @param Request $request
     * @param $provider
     * @return mixed
     */
    public function loginThirdParty(Request $request, $provider)
    {
        return $this->auth->loginThirdParty($request->all(), $provider);
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getLogout()
    {
        $provider     = \Cookie::get(config('access.socialite_session_name'));
        $cookie       = \Cookie::forget(config('access.socialite_session_name'));
        if($provider != 'idvg'){
            $this->auth->logout();

            unset($_COOKIE['mywork_sso_token']);
            setcookie('mywork_sso_token', null, -1, '/');

            /**
             * Remove the socialite session variable if exists
             */
            if (app('session')->has(config('access.socialite_session_name'))) {
                app('session')->forget(config('access.socialite_session_name'));
            }

            return redirect()->route('home')->withCookie($cookie);
        } else {
            return redirect()->route('idvg.logout',['uri' => base64_encode(url('/'))])->withCookie($cookie);
        }
    }

    /**
     * @param $token
     * @return mixed
     * @throws \App\Exceptions\GeneralException
     */
    public function confirmAccount($token)
    {
        //Don't know why the exception handler is not catching this
        try {
            $this->auth->confirmAccount($token);
            return redirect()->route('home')->withFlashSuccess(trans("auth.confirmed_success"));
        } catch (GeneralException $e) {
            return redirect()->back()->withInput()->withFlashDanger($e->getMessage());
        }
    }

    /**
     * @param $user_id
     * @return mixed
     */
    public function resendConfirmationEmail($user_id)
    {
        //Don't know why the exception handler is not catching this
        try {
            $this->auth->resendConfirmationEmail($user_id);
            return redirect()->route('home')->withFlashSuccess(trans('auth.confirm_resent'));
        } catch (GeneralException $e) {
            return redirect()->back()->withInput()->withFlashDanger($e->getMessage());
        }
    }

    /**
     * Helper methods to get laravel's ThrottleLogin class to work with this package
     */

    /**
     * Get the path to the login route.
     *
     * @return string
     */
    public function loginPath()
    {
        return property_exists($this, 'loginPath') ? $this->loginPath : '/auth/login';
    }

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function loginUsername()
    {
        return property_exists($this, 'username') ? $this->username : 'email';
    }

    /**
     * Determine if the class is using the ThrottlesLogins trait.
     *
     * @return bool
     */
    protected function isUsingThrottlesLoginsTrait()
    {
        return in_array(
            ThrottlesLogins::class, class_uses_recursive(get_class($this))
        );
    }
}
