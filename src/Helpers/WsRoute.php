<?php

namespace Wasateam\Laravelapistone\Helpers;

use Illuminate\Support\Facades\Route;

class WsRoute
{
  public static function get_resource_scope_routes($controller, $model_name, $scopes)
  {

    if (in_array('read', $scopes)) {
      Route::group([
        "middleware" => ["wasascopes:{$model_name}-read"],
      ], function () use ($model_name, $controller) {
        Route::get($model_name, [$controller, 'index']);
        Route::get("{$model_name}/{id}", [$controller, 'show']);
      });
    }

    if (in_array('edit', $scopes)) {
      Route::group([
        "middleware" => ["wasascopes:{$model_name}-edit"],
      ], function () use ($model_name, $controller) {
        Route::post($model_name, [$controller, 'store']);
        Route::patch("{$model_name}/{id}", [$controller, 'update']);
        Route::delete("{$model_name}/{id}", [$controller, 'destroy']);
      });
    }
  }
}
