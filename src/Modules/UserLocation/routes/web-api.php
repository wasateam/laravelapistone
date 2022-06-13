<?php

use \Wasateam\Laravelapistone\Modules\UserLocation\App\Controllers\UserLocationController;

\Route::group([
  "middleware" => ["auth:user"],
], function () {
  \Route::group([
    "middleware" => ["scopes:user"],
  ], function () {
    \Route::get("/my/user_location/recent", [UserLocationController::class, 'show_user_my_recent']);
    \Route::post("/my/user_location", [UserLocationController::class, 'store_user_my']);
  });
});
