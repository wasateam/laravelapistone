<?php

namespace Wasateam\Laravelapistone\Helpers;

use Wasateam\Laravelapistone\Helpers\AuthHelper;
use Wasateam\Laravelapistone\Models\User;

class TestHelper
{
  public static function getTestUserToken()
  {
    $user        = self::getTestUser();
    $tokenResult = $user->createToken('Personal Access Token', AuthHelper::getUserScopes($user));
    return $tokenResult->accessToken;
  }

  public static function getTestUser()
  {
    $user = User::where('email', 'cowabunga@haha.com')->first();
    if (!$user) {
      User::factory()
        ->cowabunga()
        ->count(1)
        ->create();
      $user = User::where('email', 'cowabunga@haha.com')->first();
    }
    return $user;
  }
}
