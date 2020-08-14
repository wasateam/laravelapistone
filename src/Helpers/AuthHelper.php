<?php

namespace Wasateam\Laravelapistone\Helpers;

class AuthHelper
{
  public static function getSetting($controller)
  {
    $setting                       = collect();
    $setting->model                = isset($controller->model) ? $controller->model : 'App\User';
    $setting->name                 = isset($controller->name) ? $controller->name : 'user';
    $setting->table                = isset($controller->table) ? $controller->table : 'users';
    $setting->guard                = isset($controller->guard) ? $controller->guard : 'api';
    $setting->scopes_from_database = isset($controller->scopes_from_database) ? $controller->scopes_from_database : 0;
    $setting->scopes               = isset($controller->scopes) ? $controller->scopes : [];
    $setting->default_scopes       = isset($controller->default_scopes) ? $controller->default_scopes : [];
    $setting->resource             = isset($controller->resource) ? $controller->resource : [];
    return $setting;
  }
}
