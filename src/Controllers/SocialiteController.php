<?php

namespace Wasateam\Laravelapistone\Controllers;

// use App\Services\SocialiteAccountService;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Socialite;
use Wasateam\Laravelapistone\Services\SocialiteAccountService;

class SocialiteController extends Controller
{
  public function googleCallback(SocialiteAccountService $service, Request $request)
  {
    return $service->createOrGetUser(Socialite::driver('google')->stateless()->user(), 'google');
  }

  public function redirectLineToProvider()
  {
    return Socialite::with('line')->redirect();
  }
  /**
   * Redirect the user to the GitHub authentication page.
   *
   * @return \Illuminate\Http\Response
   */
  public function redirectToProvider($provider)
  {
    return Socialite::driver($provider)->redirect();
  }
}
