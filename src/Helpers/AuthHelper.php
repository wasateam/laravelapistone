<?php

namespace Wasateam\Laravelapistone\Helpers;

class AuthHelper
{
  // public static function getSetting($controller)
  // {
  //   $setting                       = collect();
  //   $setting->model                = isset($controller->model) ? $controller->model : 'App\User';
  //   $setting->name                 = isset($controller->name) ? $controller->name : 'user';
  //   $setting->table                = isset($controller->table) ? $controller->table : 'users';
  //   $setting->guard                = isset($controller->guard) ? $controller->guard : 'api';
  //   $setting->scopes_from_database = isset($controller->scopes_from_database) ? $controller->scopes_from_database : 0;
  //   $setting->is_active_check      = isset($controller->is_active_check) ? $controller->is_active_check : 0;
  //   $setting->scopes               = isset($controller->scopes) ? $controller->scopes : [];
  //   $setting->default_scopes       = isset($controller->default_scopes) ? $controller->default_scopes : [];
  //   $setting->resource             = isset($controller->resource) ? $controller->resource : [];
  //   return $setting;
  // }

  public static function getUserScopes($user)
  {
    $scopes = $user->scopes ? $user->scopes : [];
    if (config('stone.auth.has_role')) {
      foreach ($user->roles as $role) {
        $scopes = array_merge($scopes, $role->scopes);
      }
    }
    return $scopes;
  }
}
