<?php

namespace Wasateam\Laravelapistone\Helpers;

use Illuminate\Support\Facades\Route;
use Wasateam\Laravelapistone\Controllers\CMSAdminController;
use Wasateam\Laravelapistone\Controllers\TulpaPageController;
use Wasateam\Laravelapistone\Controllers\TulpaSectionController;
use Wasateam\Laravelapistone\Controllers\TulpaSectionTemplateController;

class RoutesHelper
{
  public static function admin_routes()
  {
    Route::group([
      'prefix' => 'auth',
    ], function () {
      Route::post('/signin', [CMSAdminController::class, 'signin']);
      Route::group([
        "middleware" => ["auth:admin", "scopes:admin"],
      ], function () {
        Route::post('/signout', [CMSAdminController::class, 'signout']);
        Route::get('/user', [CMSAdminController::class, 'user']);
        Route::patch('/user', [CMSAdminController::class, 'update']);
        if (env('SIGNED_URL_MODE') == 'gcs') {
          Route::get("/avatar/upload_url/{filename}", [CMSAdminController::class, 'get_avatar_upload_url']);
        } else {
          Route::put("/avatar/{filename}", [CMSAdminController::class, 'avatar_upload']);
        }
      });
    });
  }

  public static function tulpa_routes()
  {
    Route::resource('tulpa_page', TulpaPageController::class)->only([
      'index', 'show', 'store', 'update', 'destroy',
    ])->shallow();
    Route::resource('tulpa_section', TulpaSectionController::class)->only([
      'index', 'show', 'store', 'update', 'destroy',
    ])->shallow();
    Route::resource('tulpa_section_template', TulpaSectionTemplateController::class)->only([
      'index', 'show', 'store', 'update', 'destroy',
    ])->shallow();
  }
}
