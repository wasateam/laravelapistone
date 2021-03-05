<?php

namespace Wasateam\Laravelapistone\Helpers;

use Illuminate\Support\Facades\Route;
use Wasateam\Laravelapistone\Controllers\TulpaPageController;
use Wasateam\Laravelapistone\Controllers\TulpaSectionController;
use Wasateam\Laravelapistone\Controllers\TulpaSectionTemplateController;

class RoutesHelper
{
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
