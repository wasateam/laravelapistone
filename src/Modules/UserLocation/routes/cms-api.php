<?php

use \Wasateam\Laravelapistone\Modules\UserLocation\App\Controllers\UserLocationController;

\Route::group([
  "middleware" => [
    "wasascopes:user_location-read",
  ],
], function () {
  \Route::get("user_location", [UserLocationController::class, 'index']);
  \Route::get("user_location/{id}", [UserLocationController::class, 'show']);
});
