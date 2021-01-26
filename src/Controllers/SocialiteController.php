<?php

namespace Wasateam\Laravelapistone\Controllers;

// use App\Services\SocialiteAccountService;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Socialite;
use Wasateam\Laravelapistone\Services\SocialiteAccountService;

class SocialiteController extends Controller
{
  public function googleCallback(SocialiteAccountService $service, Request $request)
  {
    return $service->createOrGetUser(Socialite::driver('google')->stateless()->user(), 'google');
  }
}
