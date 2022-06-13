<?php

use \Wasateam\Laravelapistone\Modules\UserRelation\App\Controllers\UserRelationController;

\Route::group([
  "middleware" => [
    "wasascopes:user_relation-read",
  ],
], function () {
  \Route::get("user_relation", [UserRelationController::class, 'index']);
  \Route::get("user_relation/{id}", [UserRelationController::class, 'show']);
});
