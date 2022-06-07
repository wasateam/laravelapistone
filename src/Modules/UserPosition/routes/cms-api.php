<?php

use \Wasateam\Laravelapistone\Modules\UserPosition\App\Controllers\UserPositionController;

\Route::group([
  "middleware" => [
    "wasascopes:user_position-read",
  ],
], function () {
  \Route::get("user_position", [UserPositionController::class, 'index']);
  \Route::get("user_position/{id}", [UserPositionController::class, 'show']);
});
