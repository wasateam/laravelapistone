<?php

namespace Wasateam\Laravelapistone\Middleware;

use Closure;
use Wasateam\Laravelapistone\Helpers\AuthHelper;
use Auth;

class WasaScopes
{
  /**
   * Handle an incoming request.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \Closure  $next
   * @param  string|null  $scope
   * @return mixed
   */
  public function handle($request, Closure $next, $scopes)
  {
    // if ($request->lang) {
    //   App::setLocale($request->lang);
    // }
    $user = Auth::user();
    if (!$user) {
      throw new \Wasateam\Laravelapistone\Exceptions\AuthException;
    }
    $user_scopes = AuthHelper::getUserScopes($user);
    if (in_array('wall-passer', $user_scopes)) {
      return $next($request);
    }

    $scopes_arr = explode(',', $scopes);
    $check      = 0;
    foreach ($user_scopes as $user_scope) {
      foreach ($scopes_arr as $scope) {
        if ($user_scope == $scope) {
          $check = 1;
        }
      }
    }
    if ($check == 0) {
      throw new \Wasateam\Laravelapistone\Exceptions\AuthException;
    }

    return $next($request);
  }
}
