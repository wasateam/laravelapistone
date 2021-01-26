<?php

namespace Wasateam\Laravelapistone\Services;

use App\Models\User;
use Carbon\Carbon;
use Laravel\Socialite\Contracts\User as ProviderUser;

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
        $user->save();
        $user->markEmailAsVerified();
      }
      $account->user()->associate($user);
      $account->save();
    }
    $tokenResult  = $user->createToken('Personal Access Token');
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
