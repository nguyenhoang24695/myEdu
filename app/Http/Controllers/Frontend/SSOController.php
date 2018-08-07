<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

/**
 * Class SSOController
 * @package App\Http\Controllers\Frontend
 */
class SSOController extends BaseController
{
    use AuthenticatesUsers;

    public function setCookie(Request $request)
    {
        $token = $request->get('token');
        if ($token) {
            $response = new \Illuminate\Http\Response();
            $response->withCookie(cookie('mywork_sso_token', $token, 3600));
            return $response; exit;
        } else {
            return null;
        }
    }

    public function clearCookie()
    {
        $cookie = \Cookie::forget('mywork_sso_token');
        $response = new \Illuminate\Http\Response();
        $response->withCookie($cookie);

        access()->logout();

        return $response; exit;
    }
}