<?php
use Illuminate\Support\Facades\Route;

# Auth
\Wasateam\Laravelapistone\Helpers\RoutesHelper::auth_routes([
  "signin",
  "signout",
  "userget",
  "userpatch",
]);

Route::group([
  "middleware" => ["auth:admin"],
], function () {
  # Boss
  Route::group([
    "middleware" => ["scopes:boss"],
  ], function () {
    \Wasateam\Laravelapistone\Helpers\RoutesHelper::cms_boss_routes();
  });

  # Admin
  Route::group([
    "middleware" => ["scopes:admin"],
  ], function () {
    \Wasateam\Laravelapistone\Helpers\RoutesHelper::cms_admin_routes();
  });
});

# Public
\Wasateam\Laravelapistone\Helpers\RoutesHelper::cms_public_routes();
