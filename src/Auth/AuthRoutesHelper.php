<?php

namespace Wasateam\Laravelapistone\Auth;

use Illuminate\Support\Facades\Route;

class AuthRoutesHelper
{
  public static function userAuthRoutes($controller_name = 'UserController', $name = "user", $guard = "api")
  {
    Route::group([
      "prefix" => $name,
    ], function () use ($controller_name, $guard, $name) {
      Route::post("signup", "{$controller_name}@signup");
      Route::post("signin", "{$controller_name}@signin");
      Route::group([
        "middleware" => ["auth:{$guard}", "client:{$name}"],
      ], function () use ($controller_name, $guard) {
        Route::get("user", "{$controller_name}@user");
        Route::post('signout', "{$controller_name}@signout");
      });
    });
  }
}
