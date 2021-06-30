<?php

namespace Wasateam\Laravelapistone\Helpers;

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
}
