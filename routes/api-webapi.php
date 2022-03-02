<?php
use Illuminate\Support\Facades\Route;

# Auth
\Wasateam\Laravelapistone\Helpers\RoutesHelper::auth_routes([
  "signin",
  "signup",
  "signout",
  "userget",
  "userpatch",
  "passwordpatch",
  "forgetpassword",
  "email_verify",
]);

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
\Wasateam\Laravelapistone\Helpers\RoutesHelper::webapi_public_routes();
