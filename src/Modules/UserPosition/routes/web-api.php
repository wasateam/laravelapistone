<?php

use \Wasateam\Laravelapistone\Modules\UserPosition\App\Controllers\UserPositionController;

\Route::group([
  "middleware" => ["auth:user"],
], function () {
  \Route::group([
    "middleware" => ["scopes:user"],
  ], function () {
    \Route::get("/my/user_position/recent", [UserPositionController::class, 'show_user_my_recent']);
    \Route::post("/my/user_position", [UserPositionController::class, 'store_user_my']);
  });
});
