<?php
use Illuminate\Support\Facades\Route;

# Auth
\Wasateam\Laravelapistone\Helpers\RoutesHelper::auth_routes();

Route::group([
  "middleware" => ["auth:user"],
], function () {

  Route::group([
    "middleware" => ["scopes:user"],
  ], function () {
    \Wasateam\Laravelapistone\Helpers\RoutesHelper::webapi_auth_routes();

    Route::group([
      "middleware" => ["isuser"],
    ], function () {
      \Wasateam\Laravelapistone\Helpers\RoutesHelper::webapi_isuser_routes();

    });
  });
});

# Public

Route::group([
  "middleware" => [
    "scopes:user",
    "throttle:-1,1",
  ],
], function () {
  \Wasateam\Laravelapistone\Helpers\RoutesHelper::webapi_public_routes();
});
