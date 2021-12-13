<?php

namespace Wasateam\Laravelapistone\Helpers;

use Auth;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

class AuthHelper
{
  public static function getUserScopes($user)
  {
    $scopes = $user->scopes ? $user->scopes : [];
    if (config('stone.admin_role')) {
      foreach ($user->roles as $role) {
        $scopes = array_merge($scopes, $role->scopes);
      }
    }
    return $scopes;
  }

  public static function getAppIdFromRequest($request, $app_name = 'app')
  {
    $app_id = "";
    if ($request->{$app_name}) {
      $app_id = $request->{$app_name};
    } else if ($request->{"{$app_name}_id"}) {
      $app_id = $request->{"{$app_name}_id"};
    }
    return $app_id;
  }

  public static function checkUserInApp($user, $app_id, $app_name, $app_field_name)
  {

    $user_apps    = $user->{$app_field_name};
    $user_app_ids = $user_apps->map(function ($user_app) {
      return $user_app->id;
    });

    $in_app = $user_app_ids->some(function ($user_app_id) use ($app_id) {
      return $user_app_id == $app_id;
    });

    return $in_app;
  }

  public static function getScopesInApp($user, $app_id, $app_name = "app", $auth_name = "user", $id_name = "app")
  {
    $user_roles = [];
    $user_roles = $user->{"{$app_name}_roles"}->filter(function ($item) use ($app_id, $id_name) {
      return $item->{"{$id_name}_id"} == $app_id;
    });
    $app_infos = $user->{"{$auth_name}_{$app_name}_infos"}->filter(function ($item) use ($app_id, $id_name) {
      return $item->{"{$id_name}_id"} == $app_id;
    });
    $all_scopes = [];
    foreach ($user_roles as $user_role) {
      if ($user_role->scopes) {
        $all_scopes = array_merge($all_scopes, $user_role->scopes);
      }
    }
    foreach ($app_infos as $app_info) {
      if ($app_info->scopes) {
        $all_scopes = array_merge($all_scopes, $app_info->scopes);
      }
    }
    return $all_scopes;
  }

  public static function checkHasScope($scopes, $target)
  {

    return Arr::where($scopes, function ($value) use ($target) {
      return Str::contains($target, $value);
    });
  }

  public static function getAuthScope($request, $app_name = "app", $app_field_name = "apps", $auth_name = "user", $model_id_name = "model")
  {
    $user = Auth::user();
    if (!$user) {
      return false;
    }

    // App Id
    $app_id = Self::getAppIdFromRequest($request, $app_name);

    // In App Check
    $in_app = Self::checkUserInApp($user, $app_id, $app_name, $app_field_name);
    if (!$in_app) {
      return false;
    }

    // All Scopes
    $all_scopes = Self::getScopesInApp($user, $app_id, $app_name, $auth_name, $model_id_name);

    if (in_array('wall-passer', $all_scopes)) {
      return true;
    } else if (!$has_scope) {
      return false;
    }

    // Has Scope
    $api_name  = Route::currentRouteName();
    $has_scope = Self::checkHasScope($all_scopes, $api_name);

    return true;
  }

  public static function checkAuthScope($request, $filters = [], $custom_scope_handler = null)
  {
    $has_scope = true;
    if (count($filters) && $request) {
      foreach ($filters as $filter) {
        if ($request->$filter || $request->{"{$filter}_id"}) {
          $has_scope = Self::getAuthScope($request, $filter, $filter . "s", config('stone.auth.model_name'), "app");
        }
      }
    }
    if ($custom_scope_handler) {
      $has_scope = $custom_scope_handler($has_scope);
    }
    return $has_scope;
  }

  public static function getCustomerId($model, $customer_id_config)
  {
    if ($customer_id_config['logic'] == 'year-serial') {
      $user_count = $model::whereYear('created_at', Carbon::now()->year)->count();
      return $customer_id_config['prefix'] . Carbon::now()->year . str_pad($user_count + 1, 6, '0', STR_PAD_LEFT);
    }
  }
}
