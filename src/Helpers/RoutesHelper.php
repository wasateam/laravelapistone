<?php

namespace Wasateam\Laravelapistone\Helpers;

use Illuminate\Support\Facades\Route;
use Wasateam\Laravelapistone\Controllers\AuthController;
use Wasateam\Laravelapistone\Controllers\FileController;
use Wasateam\Laravelapistone\Controllers\ImageController;
use Wasateam\Laravelapistone\Controllers\TulpaPageController;
use Wasateam\Laravelapistone\Controllers\TulpaSectionController;
use Wasateam\Laravelapistone\Controllers\TulpaSectionTemplateController;

class RoutesHelper
{
  public static function auth_routes($routes = [
    "signin",
    "signup",
    "signout",
    "userget",
    "userpatch",
    "avatarpatch",
  ]) {
    $model_name = config('stone.auth.model_name');
    $auth_scope = config('stone.auth.auth_scope');
    Route::group([
      'prefix' => 'auth',
    ], function () use ($routes, $model_name, $auth_scope) {
      if (in_array('signin', $routes)) {
        Route::post('/signin', [AuthController::class, 'signin']);
      }
      if (in_array('signup', $routes)) {
        Route::post('/signup', [AuthController::class, 'signup']);
      }
      Route::group([
        "middleware" => ["auth:{$model_name}", "scopes:{$auth_scope}"],
      ], function () use ($routes) {
        if (in_array('signout', $routes)) {
          Route::post('/signout', [AuthController::class, 'signout']);
        }
        if (in_array('userget', $routes)) {
          Route::get('/user', [AuthController::class, 'user']);
        }
        if (in_array('userpatch', $routes)) {
          Route::patch('/user', [AuthController::class, 'update']);
        }
        if (in_array('avatarpatch', $routes)) {
          if (env('SIGNED_URL_MODE') == 'gcs') {
            Route::get("/avatar/upload_url/{filename}", [AuthController::class, 'get_avatar_upload_url']);
          } else {
            Route::put("/avatar/{filename}", [AuthController::class, 'avatar_upload']);
          }
        }
      });
    });
  }

  public static function tulpa_routes()
  {
    Route::resource('tulpa_page', TulpaPageController::class)->only([
      'index', 'show', 'store', 'update', 'destroy',
    ])->shallow();
    Route::resource('tulpa_section', TulpaSectionController::class)->only([
      'index', 'show', 'store', 'update', 'destroy',
    ])->shallow();
    Route::resource('tulpa_section_template', TulpaSectionTemplateController::class)->only([
      'index', 'show', 'store', 'update', 'destroy',
    ])->shallow();
  }

  public static function image_routes()
  {
    $storage_service = config('stone.storage.service');
    if ($storage_service == 'gcs') {
      Route::get('image/upload_url', [ImageController::class, 'get_upload_url']);
    }
    Route::resource('image', ImageController::class)->only([
      'index', 'show', 'store', 'update', 'destroy',
    ])->shallow();
  }

  public static function file_routes()
  {
    $storage_service = config('stone.storage.service');
    Route::resource('file', FileController::class)->only([
      'index', 'show', 'store', 'update', 'destroy',
    ])->shallow();
    if ($storage_service == 'gcs') {
      Route::get('file/upload_url', [FileController::class, 'get_upload_url']);
    }
  }
}
