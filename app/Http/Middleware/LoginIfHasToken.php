<?php

namespace App\Http\Middleware;

use App\Repositories\Frontend\Auth\AuthenticationContract;
use Closure;

/**
 * Class LoginIfHasToken
 * @package App\Http\Middleware
 */
class LoginIfHasToken
{
    protected $auth;

    public function __construct(AuthenticationContract $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if ($request->path() != 'sso/set-cookie') {
            $token = \Cookie::get('mywork_sso_token');
            if (access()->guest() && $token) {
                $this->auth->loginWithMWID($token);
            }
        }

        return $next($request);
    }
}
