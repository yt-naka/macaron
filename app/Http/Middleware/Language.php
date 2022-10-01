<?php

namespace App\Http\Middleware;

use Closure;

use App; //追加

class Language
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $first_language = explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE'])[0];
        App::setLocale(substr($first_language, 0, 2));
        return $next($request);
    }
}
