<?php

use \Wasateam\Laravelapistone\Modules\UserRelation\App\Controllers\UserRelationController;

\Route::group([
  "middleware" => ["auth:user"],
], function () {
  \Route::group([
    "middleware" => ["scopes:user"],
  ], function () {
    \Route::get("/my/user_relation/recent", [UserRelationController::class, 'show_user_my_recent']);
    \Route::post("/my/user_relation", [UserRelationController::class, 'store_user_my']);
  });
});
