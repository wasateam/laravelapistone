<?php
use Illuminate\Support\Facades\Route;
use Wasateam\Laravelapistone\Controllers\CMSAdminController;

error_log(config('stone.mode'));

Route::get('test', function () {
  return 'aaa';
});

if (config('stone.auth') && config('stone.mode') == 'cms') {
  $routes = [
    'index',
    'show',
    'store',
    'update',
    'destroy',
  ];
  if (config('stone.admin_blur')) {
    Route::resource('cmser', CMSAdminController::class)->only($routes)->shallow();
  } else {
    Route::resource('admin', CMSAdminController::class)->only($routes)->shallow();
  }
}
