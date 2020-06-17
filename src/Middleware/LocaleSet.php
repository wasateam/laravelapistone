<?php

namespace Wasateam\Laravelapistone\Middleware;

use Closure;
use App;

class LocaleSet
{
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
    if ($request->lang) {
      App::setLocale($request->lang);
    }
    return $next($request);
  }
}
