<?php

namespace Wasateam\Laravelapistone\Helpers;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class AuthHelper
{
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

  public static function getAppIdFromRequest($request, $app_name = 'app')
  {
    $app_id;
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

  public static function getScopesInApp($user, $app_id, $app_name)
  {
    $all_scopes = [];
    $user_roles = $user->{"{$app_name}_roles"}->filter(function ($item) use ($app_id) {
      if (!$item->{"{$app_name}_id"} && $item->is_default) {
        return true;
      } else {
        return $item->{"{$app_name}_id"} == $app_id;
      }
    });
    $app_scopes = $user->{"{$app_name}_scopes"}->filter(function ($item) use ($app_id) {
      if (!$item->{"{$app_name}_id"} && $item->is_default) {
        return true;
      } else {
        return $item->{"{$app_name}_id"} == $app_id;
      }
    });
    foreach ($user_roles as $user_role) {
      if ($user_role->scopes) {
        $all_scopes = array_merge($all_scopes, $user_role->scopes);
      }
    }
    foreach ($app_scopes as $app_scope) {
      if ($app_scope->scopes) {
        $all_scopes = array_merge($all_scopes, $app_scope->scopes);
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
}
