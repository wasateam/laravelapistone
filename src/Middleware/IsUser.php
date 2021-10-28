<?php

namespace Wasateam\Laravelapistone\Middleware;

use Auth;
use Closure;
use Illuminate\Http\Request;

class IsUser
{
  public function handle(Request $request, Closure $next)
  {
    // User

    $user = Auth::user();
    if (!$user) {
      return response()->json([
        'message' => ':(',
      ], 403);
    }

    if (!$request->has('user')) {
      return response()->json([
        'message' => ':(',
      ], 403);
    }

    if ($request->user != $user->id) {
      return response()->json([
        'message' => ':(',
      ], 403);
    }

    return $next($request);
  }
}
