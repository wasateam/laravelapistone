<?php

namespace Wasateam\Laravelapistone\Auth;

use Illuminate\Support\Facades\Route;

class AuthRoutesHelper
{
  public static function userAuthRoutes($controller_name = 'UserController', $name = "user", $guard = "api", $routes = ['signup', 'signin', 'signout', 'show', 'update', 'password_update', 'avatar_upload'], $user_scope = null)
  {
    if (!$user_scope) {
      $user_scope = $name;
    }
    Route::group([
      "prefix" => $name,
    ], function () use ($controller_name, $guard, $routes, $user_scope) {
      if (in_array('signup', $routes)) {
        Route::post("signup", "{$controller_name}@signup");
      }
      if (in_array('signin', $routes)) {
        Route::post("signin", "{$controller_name}@signin");
      }
      Route::group([
        "middleware" => ["auth:{$guard}", "scopes:{$user_scope}"],
      ], function () use ($controller_name, $guard, $routes) {
        if (in_array('signout', $routes)) {
          Route::post('signout', "{$controller_name}@signout");
        }
        if (in_array('show', $routes)) {
          Route::get("user", "{$controller_name}@user");
        }
        if (in_array('update', $routes)) {
          Route::patch("user", "{$controller_name}@update");
        }
        if (in_array('password_update', $routes)) {
          Route::patch("user", "{$controller_name}@password_update");
        }
        if (in_array('avatar_upload', $routes)) {
          if (env('SIGNED_URL_MODE') == 'gcs') {
            Route::get("avatar/upload_url/{filename}", "{$controller_name}@get_avatar_upload_url");
          } else {
            Route::put("avatar/{filename}", "{$controller_name}@avatar_upload");
          }
        }
      });
    });
  }
}
