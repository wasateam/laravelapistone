<?php
use Illuminate\Support\Facades\Route;

# Auth
\Wasateam\Laravelapistone\Helpers\RoutesHelper::auth_routes([
  "signin",
  "signout",
  "userget",
  "userpatch",
  "avatarpatch",
]);

Route::group([
  "middleware" => ["auth:user"],
], function () {

  Route::group([
    "middleware" => ["scopes:user"],
  ], function () {
    \Wasateam\Laravelapistone\Helpers\RoutesHelper::webapi_auth_routes();
  });
});

# Public
\Wasateam\Laravelapistone\Helpers\RoutesHelper::webapi_public_routes();
