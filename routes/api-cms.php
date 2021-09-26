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

// Boss
Route::group([
  "middleware" => ["auth:admin", "scopes:boss"],
], function () {

  # Boss
  \Wasateam\Laravelapistone\Helpers\RoutesHelper::cms_boss_routes();
});

// Admin
Route::group([
  "middleware" => ["auth:admin", "scopes:admin"],
], function () {

  # admin
  \Wasateam\Laravelapistone\Helpers\RoutesHelper::cms_admin_routes();
});
