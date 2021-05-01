<?php

namespace Wasateam\Laravelapistone\Helpers;

use Illuminate\Support\Facades\Route;
use Wasateam\Laravelapistone\Controllers\AuthController;
use Wasateam\Laravelapistone\Controllers\PocketFileController;
use Wasateam\Laravelapistone\Controllers\PocketImageController;
use Wasateam\Laravelapistone\Controllers\SocialiteController;
use Wasateam\Laravelapistone\Controllers\TagController;
use Wasateam\Laravelapistone\Controllers\TulpaPageController;
use Wasateam\Laravelapistone\Controllers\TulpaSectionController;
use Wasateam\Laravelapistone\Controllers\TulpaSectionTemplateController;
use Wasateam\Laravelapistone\Controllers\UserController;
use Wasateam\Laravelapistone\Controllers\WsBlogController;

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

  public static function socialite_routes($routes = [
    "google",
    "facebook",
    "line",
  ]) {

    Route::group([
      'prefix' => 'auth',
    ], function () use ($routes) {

      if (in_array('google', $routes)) {
        Route::get('signin/google', [SocialiteController::class, 'googleCallback']);
      }
      if (in_array('facebook', $routes)) {
        Route::get('signin/facebook', [SocialiteController::class, 'facebookCallback']);
      }
      if (in_array('line', $routes)) {
        Route::get('signin/line', [SocialiteController::class, 'lineCallback']);
      }
    });
  }

  public static function tulpa_routes($routes = [
    'index',
    'show',
    'store',
    'update',
    'destroy',
  ]) {
    Route::resource('tulpa_page', TulpaPageController::class)->only($routes)->shallow();
    Route::resource('tulpa_section', TulpaSectionController::class)->only($routes)->shallow();
    Route::resource('tulpa_section_template', TulpaSectionTemplateController::class)->only($routes)->shallow();
  }

  public static function ws_blog_routes($routes = [
    'index',
    'show',
    'store',
    'update',
    'destroy',
    'image_get_upload_url',
    'image_upload_complete',
  ]) {
    Route::resource('ws_blog', WsBlogController::class)->only($routes)->shallow();
    if (in_array('image_get_upload_url', $routes)) {
      Route::get('/ws_blog/image/upload_url', [WsBlogController::class, 'image_get_upload_url']);
    }
    if (in_array('image_upload_complete', $routes)) {
      Route::post('/ws_blog/image/upload_complete', [WsBlogController::class, 'image_upload_complete']);
    }
  }

  public static function tag_routes($routes = [
    'index',
    'show',
    'store',
    'update',
    'destroy',
  ]) {
    Route::resource('ws_blog', TagController::class)->only($routes)->shallow();
  }

  public static function pocket_image_routes()
  {
    $storage_service = config('stone.storage.service');
    Route::resource('pocket_image', PocketImageController::class)->only([
      'index', 'store', 'destroy',
    ])->shallow();
  }

  public static function pocket_file_routes()
  {
    $storage_service = config('stone.storage.service');
    Route::resource('pocket_file', PocketFileController::class)->only([
      'index', 'store', 'destroy',
    ])->shallow();
  }

  public static function user_crud_routes($routes = [
    'index',
    'show',
    'store',
    'update',
    'destroy',
    'image_get_upload_url',
    'image_upload_complete',
  ]) {
    Route::resource('user', UserController::class)->only($routes)->shallow();
    if (in_array('avatar_get_upload_url', $routes)) {
      Route::get('/ws_blog/avatar/upload_url', [UserController::class, 'avatar_get_upload_url']);
    }
    if (in_array('avatar_upload_complete', $routes)) {
      Route::post('/ws_blog/avatar/upload_complete', [UserController::class, 'avatar_upload_complete']);
    }
  }
}
