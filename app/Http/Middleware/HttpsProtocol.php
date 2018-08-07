<?php
/**
 * Created by PhpStorm.
 * User: hocvt
 * Date: 4/16/16
 * Time: 20:41
 */

namespace App\Http\Middleware;

use Closure;

class HttpsProtocol {
	public function handle($request, Closure $next)
	{
		if (!$request->secure() && app()->environment() == 'production') {
			return redirect()->secure($request->getRequestUri())->setStatusCode(301);
		}

		return $next($request);
	}
}