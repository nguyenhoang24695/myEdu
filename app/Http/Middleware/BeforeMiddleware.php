<?php
/**
 * Created by PhpStorm.
 * User: hocvt
 * Date: 9/21/15
 * Time: 14:47
 */

namespace App\Http\Middleware;

use Closure;

class BeforeMiddleware
{
    public function handle($request, Closure $next)
    {
        // Perform action
        return $next($request);
    }
}