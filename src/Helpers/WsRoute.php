<?php

namespace Wasateam\Laravelapistone\Helpers;

use Illuminate\Support\Facades\Route;

class WsRoute
{
  public static function get_resource_scope_routes($controller, $model_name, $scopes)
  {

    if (in_array('read-admin-my', $scopes)) {
      Route::group([
        "middleware" => [
          "isadmin",
          "wasascopes:{$model_name}-read-admin-my",
        ],
      ], function () use ($model_name, $controller) {
        Route::get($model_name, [$controller, 'index']);
        Route::get("{$model_name}/{id}", [$controller, 'show']);
      });
    }

    if (in_array('create-admin-my', $scopes)) {
      Route::group([
        "middleware" => [
          "isadmin",
          "wasascopes:{$model_name}-create-admin-my",
        ],
      ], function () use ($model_name, $controller) {
        Route::post($model_name, [$controller, 'store']);
      });
    }

    if (in_array('update-admin-my', $scopes)) {
      Route::group([
        "middleware" => [
          "isadmin",
          "wasascopes:{$model_name}-update-admin-my",
        ],
      ], function () use ($model_name, $controller) {
        Route::patch("{$model_name}/{id}", [$controller, 'update']);
      });
    }

    if (in_array('delete-admin-my', $scopes)) {
      Route::group([
        "middleware" => [
          "isadmin",
          "wasascopes:{$model_name}-delete-admin-my",
        ],
      ], function () use ($model_name, $controller) {
        Route::delete("{$model_name}/{id}", [$controller, 'destroy']);
      });
    }

    if (in_array('read', $scopes)) {
      Route::group([
        "middleware" => ["wasascopes:{$model_name}-read"],
      ], function () use ($model_name, $controller) {
        Route::get($model_name, [$controller, 'index']);
        Route::get("{$model_name}/{id}", [$controller, 'show']);
      });
    }

    if (in_array('create', $scopes)) {
      Route::group([
        "middleware" => ["wasascopes:{$model_name}-create"],
      ], function () use ($model_name, $controller) {
        Route::post($model_name, [$controller, 'store']);
      });
    }

    if (in_array('update', $scopes)) {
      Route::group([
        "middleware" => ["wasascopes:{$model_name}-update"],
      ], function () use ($model_name, $controller) {
        Route::patch("{$model_name}/{id}", [$controller, 'update']);
      });
    }

    if (in_array('delete', $scopes)) {
      Route::group([
        "middleware" => ["wasascopes:{$model_name}-delete"],
      ], function () use ($model_name, $controller) {
        Route::delete("{$model_name}/{id}", [$controller, 'destroy']);
      });
    }

  }
}
