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
use Wasateam\Laravelapistone\Helpers\ModelHelper;
use Wasateam\Laravelapistone\Helpers\StorageHelper;

class AuthController extends Controller
{
  /**
   * Signup
   *
   * @bodyParam  email mail required Admin Email Account Example: wasa@wasateam.com
   * @bodyParam  password string required Example: 123123
   * @bodyParam  password_confirmation string required Check Password match  Example: 123123
   * @bodyParam  name string User Name  Example: wasa
   * @bodyParam  tel string
   * @response
   * {
   * "data": {
   * "id": 2,
   * "name": "wasa",
   * "email": "wasa@wasateam.com",
   * "created_at": "2020-07-11T15:06:43.000000Z",
   * "updated_at": "2020-07-11T15:06:43.000000Z"
   * }
   * }
   *
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
      'password' => $request->password,
    ]);
    if ($request->has('tel')) {
      $user->tel = $request->tel;
    }
    if ($setting->default_scopes) {
      $user->scopes = $setting->default_scopes;
    }
    $user->save();
    return (new $setting->resource($user));
  }

  /**
   * Signin
   *
   * posting
   * const response = pm.response.json();pm.collectionVariables.set("token", response.access_token);
   * in tests
   *
   * @bodyParam  email mail required Admin Email Account Example: wasa@wasateam.com
   * @bodyParam  password string required Example: 123123
   * @response
   * {
   * "access_token": "{{token here}}",
   * "expires_at": "2021-07-11 15:10:13",
   * "admin": {
   * "id": 2,
   * "created_at": "2020-07-11T15:06:43.000000Z",
   * "updated_at": "2020-07-11T15:06:43.000000Z",
   * "deleted_at": null,
   * "name": "wasa",
   * "email": "wasa@wasateam.com",
   * "email_verified_at": null
   * }
   * }
   *
   */
  public function signin(Request $request)
  {
    $setting = AuthHelper::getSetting($this);
    $request->validate([
      'email'       => 'required|email',
      'password'    => 'required|string',
      'remember_me' => 'boolean',
    ]);
    $snap = $setting->model::where('email', $request->email);
    if ($setting->is_active_check) {
      $snap = $snap->where('is_active', 1);
    }
    $user = $snap->first();
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
    if ($setting->scopes_from_database) {
      $tokenResult = $user->createToken('Personal Access Token', $user->scopes);
    } else {
      $tokenResult = $user->createToken('Personal Access Token', $setting->scopes);
    }
    $token = $tokenResult->token;
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

  /**
   * Get User
   *
   * @authenticated
   *
   * @response
   * {
   * "data": {
   * "id": 2,
   * "name": "wasa",
   * "email": "wasa@wasateam.com",
   * "created_at": "2020-07-11T15:06:43.000000Z",
   * "updated_at": "2020-07-11T15:06:43.000000Z"
   * }
   * }
   *
   */
  public function user()
  {
    $setting = AuthHelper::getSetting($this);
    $user    = Auth::user();
    if (!$user) {
      return response()->json([
        'message' => 'cannot find user.',
      ], 401);
    }
    return response()->json([
      'user'   => new $setting->resource($user),
      'scopes' => $user->scopes,
    ], 200);
  }

  /**
   * Signout
   *
   * @authenticated
   *
   * @response
   * {
   * "message": "signout successed."
   * }
   *
   */
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

  /**
   * Update
   *
   * @authenticated
   *
   * @bodyParam  password string Example: 123123
   * @bodyParam  name string User Name  Example: wasa
   * @bodyParam  avatar signedurl
   */
  public function update(Request $request)
  {
    $setting = AuthHelper::getSetting($this);
    $rules   = [
      'password' => 'string|min:6',
      'name'     => 'string|min:1|max:40',
    ];
    $validator = Validator::make($request->all(), $rules);
    if ($validator->fails()) {
      return response()->json([
        'message' => $validator->messages(),
      ], 400);
    }
    $user = Auth::user();
    if ($request->has('name')) {
      $user->name = $request->name;
    }
    if ($request->has('password')) {
      $user->password = $request->password;
    }
    if ($request->has('avatar')) {
      $user->avatar = $request->avatar;
    }
    if ($request->has('tel')) {
      $user->tel = $request->tel;
    }
    $user->save();
    return (new $setting->resource($user));
  }

  /**
   * Upload Avatar
   *
   * put binary data in request body
   *
   * @urlParam  filename string required
   */
  public function avatar_upload(Request $request, $filename)
  {
    return ModelHelper::ws_Upload($this, $request, $filename, 'avatar', 'general');
  }

  /**
   * Get Avatar Upload Url
   *
   * @urlParam  filename string required Example: avatar.png
   */
  public function get_avatar_upload_url($filename)
  {
    $setting = AuthHelper::getSetting($this);
    $user    = Auth::user();
    return StorageHelper::getGoogleUploadSignedUrlByNameAndPath($filename, "{$setting->name}/{$user->id}", 'image/png');
  }
}
