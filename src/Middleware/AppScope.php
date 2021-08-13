<?php

namespace Wasateam\Laravelapistone\Middleware;

use Auth;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Wasateam\Laravelapistone\Helpers\AuthHelper;

class AppScope
{
  public function handle(Request $request, Closure $next)
  {
    // User

    $check = AuthHelper::
    $user = Auth::user();
    if (!$user) {
      return response()->json([
        'message' => ':(',
      ], 403);
    }

    // App Id
    $app_id = AuthHelper::getAppIdFromRequest($request, 'app');

    // In App Check
    $in_app = AuthHelper::checkUserInApp($user, $app_id, 'app', 'apps');
    if (!$in_app) {
      return response()->json([
        'message' => ':(',
      ], 403);
    }

    // All Scopes
    $all_scopes = AuthHelper::getScopesInApp($user, $app_id, 'app');

    // Has Scope
    $api_name  = Route::currentRouteName();
    $has_scope = AuthHelper::checkHasScope($all_scopes, $api_name);
    if (!$has_scope) {
      return response()->json([
        'message' => ':(',
      ], 403);
    }
    return $next($request);
  }
}
