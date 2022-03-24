<?php

namespace Wasateam\Laravelapistone\Helpers;

use Wasateam\Laravelapistone\Helpers\AuthHelper;
use Wasateam\Laravelapistone\Models\Admin;
use Wasateam\Laravelapistone\Models\User;

class TestHelper
{
  public static function testIndex($tester, $url, $params = [], $status = 200, $scopes = null, $guard = null)
  {

    self::setPsasportAct($scopes, $guard);

    $response = $tester->call('GET', $url, $params);
    if ($response->status() != $status) {
      \Log::info("{$url}");
      \Log::info($response->json());
    }
    $response->assertStatus($status);
  }

  public static function testUpdate($tester, $url, $body = [], $status = 200, $scopes = null, $guard = null)
  {

    self::setPsasportAct($scopes, $guard);

    $response = $tester->call('PATCH', $url, $body);
    if ($response->status() != $status) {
      \Log::info("{$url}");
      \Log::info($response->json());
    }
    $response->assertStatus($status);
  }

  public static function testCreate($tester, $url, $body = [], $status = 201, $scopes = null, $guard = null)
  {

    self::setPsasportAct($scopes, $guard);

    $response = $tester->call('POST', $url, $body);
    if ($response->status() != $status) {
      \Log::info("{$url}");
      \Log::info($response->json());
    }
    $response->assertStatus($status);
  }

  public static function setPsasportAct($scopes = null, $guard = null)
  {
    if (!$guard) {
      if (config('stone.mode') == 'cms') {
        $guard = 'admin';
      }
      if (config('stone.mode') == 'webapi') {
        $guard = 'user';
      }
    }
    if (!$scopes) {
      if (config('stone.mode') == 'cms') {
        $scopes = ['admin'];
      }
      if (config('stone.mode') == 'webapi') {
        $scopes = ['user'];
      }
    }
    if ($guard == 'user') {
      $user = self::getTestUser();
    }
    if ($guard == 'admin') {
      $user = self::getTestAdmin();
    }

    \Laravel\Passport\Passport::actingAs(
      $user, $scopes, $guard
    );
  }

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

  public static function getTestAdmin()
  {
    $user = Admin::where('email', 'boss@wasateam.com')->first();
    return $user;
  }
}
