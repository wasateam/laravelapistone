<?php

namespace Wasateam\Laravelapistone\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Validator;
use Wasateam\Laravelapistone\Helpers\AuthHelper;

class AuthController extends Controller
{
  /**
   * Create user
   *
   * @param  [string] name
   * @param  [string] email
   * @param  [string] password
   * @param  [string] password_confirmation
   * @return [string] message
   */
  public function signup(Request $request)
  {
    $setting  = AuthHelper::getSetting($this);
    $messages = [
      'password.min' => 'password too short.',
      'email.unique' => 'email has been token.',
    ];
    $rules = [
      'email'    => "required|string|email|unique:{$setting->table}",
      'password' => 'required|string|confirmed|min:6',
      'name'     => 'required|string|min:1|max:40',
    ];
    $validator = Validator::make($request->all(), $rules, $messages);
    if ($validator->fails()) {
      return response()->json([
        'message' => $validator->messages(),
      ], 400);
    }
    $user = new $setting->model([
      'email'    => $request->email,
      'name'     => $request->name,
      'password' => Hash::make($request->password),
    ]);
    $user->save();
    return (new $setting->resource($user));
  }

  public function signin(Request $request)
  {
    $setting = AuthHelper::getSetting($this);
    $request->validate([
      'email'       => 'required|email',
      'password'    => 'required|string',
      'remember_me' => 'boolean',
    ]);
    $user = $setting->model::where('email', $request->email)->first();
    if (!$user) {
      return response()->json([
        'message' => 'find no email.',
      ], 401);
    }
    if (!Hash::check($request->password, $user->password)) {
      return response()->json([
        'message' => 'password not correct.',
      ], 401);
    }
    $tokenResult = $user->createToken('Personal Access Token', $setting->scopes);
    $token       = $tokenResult->token;
    if ($request->remember_me) {
      $token->expires_at = Carbon::now()->addWeeks(60);
    }
    $token->save();
    return response()->json([
      'access_token' => $tokenResult->accessToken,
      'expires_at'   => Carbon::parse(
        $tokenResult->token->expires_at
      )->toDateTimeString(),
      $setting->name => $user,
    ], 200);
  }

  public function user()
  {
    $setting = AuthHelper::getSetting($this);
    $user    = Auth::user();
    if (!$user) {
      return response()->json([
        'message' => 'cannot find user.',
      ], 401);
    }
    return (new $setting->resource($user));
  }

  public function signout(Request $request)
  {
    try {
      $request->user()->token()->revoke();
    } catch (\Throwable $th) {
      return response()->json([
        'message' => 'signout fail.',
      ]);
    }
    return response()->json([
      'message' => 'signout successed.',
    ]);
  }
}
