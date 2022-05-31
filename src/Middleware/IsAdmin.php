<?php

namespace Wasateam\Laravelapistone\Middleware;

use Auth;
use Closure;
use Illuminate\Http\Request;

class IsAdmin
{
  public function handle(Request $request, Closure $next)
  {
    $admin = Auth::user();
    if (!$admin) {
      return response()->json([
        'message' => ':(',
      ], 403);
    }

    if (!$request->has('user')) {
      return response()->json([
        'message' => ':(',
      ], 403);
    }

    if ($request->admin != $admin->id) {
      return response()->json([
        'message' => ':(',
      ], 403);
    }

    return $next($request);
  }
}
