<?php

namespace Wasateam\Laravelapistone\Services;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Laravel\Socialite\Contracts\User as ProviderUser;
use Wasateam\Laravelapistone\Helpers\AuthHelper;
use Wasateam\Laravelapistone\Models\User;

class SocialiteAccountService
{
  public function createOrGetUser(ProviderUser $providerUser, $provider = null)
  {
    if (!$provider) {
      return response()->json([
        'message' => 'no provider given.',
      ], 400);
    }
    $socialiteModel = null;
    if ($provider == 'google') {
      $socialiteModel = 'Wasateam\Laravelapistone\Models\SocialiteGoogleAccount';
    }
    if ($provider == 'facebook') {
      $socialiteModel = 'Wasateam\Laravelapistone\Models\SocialiteFacebookAccount';
    }
    if ($provider == 'line') {
      $socialiteModel = 'Wasateam\Laravelapistone\Models\SocialiteLineAccount';
    }
    $account = $socialiteModel::whereProvider($provider)
      ->whereProviderUserId($providerUser->getId())
      ->first();
    if ($account) {
      $user = $account->user;
    } else {
      $account                   = new $socialiteModel;
      $account->provider_user_id = $providerUser->getId();
      $account->provider         = $provider;
      $user                      = User::whereEmail($providerUser->getEmail())->first();
      if (!$user) {
        if (!$providerUser->email) {
          $providerUser->email = $providerUser->id . $providerUser->name . '@' . $provider;
        }
        $user           = new User;
        $user->email    = $providerUser->getEmail();
        $user->name     = $providerUser->getName();
        $user->password = md5(rand(1, 10000));
        $user->avatar   = $providerUser->avatar;

        if (config('stone.auth.active_check')) {
          $user->is_active = 1;
        }
        if (config('stone.auth.uuid')) {
          $user->uuid = Str::uuid();
        }
        $default_scopes = config('stone.auth.default_scopes');
        $user->scopes   = $default_scopes;
        $user->save();
        if (config('stone.auth.customer_id')) {
          $user->customer_id = AuthHelper::getCustomerId(User, config('stone.auth.customer_id'));
          $user->save();
        }
        $user->markEmailAsVerified();
      }
      $account->user()->associate($user);
      $account->save();
    }
    $tokenResult  = $user->createToken('Personal Access Token', AuthHelper::getUserScopes($user));
    $access_token = $tokenResult->accessToken;
    $user         = User::where('id', $user->id)->first();
    return response()->json([
      'access_token' => $access_token,
      'expires_at'   => Carbon::parse(
        $tokenResult->token->expires_at
      )->toDateTimeString(),
      'user'         => $user,
    ], 200);
  }
}
