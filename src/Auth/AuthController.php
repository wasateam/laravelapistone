<?php

namespace Wasateam\Laravelapistone\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Validator;
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
   *
   */
  public function signup(Request $request)
  {
    $model_name     = config('stone.auth.model_name');
    $model          = config('stone.auth.model');
    $resource       = config('stone.auth.resource');
    $auth_scope     = config('stone.auth.auth_scope');
    $default_scopes = config('stone.auth.default_scopes');

    $messages = [
      'password.min' => 'password too short.',
      'email.unique' => 'email has been token.',
    ];
    $rules = [
      'email'    => "required|string|email|unique:{$model_name}s",
      'password' => 'required|string|confirmed|min:6',
      'name'     => 'required|string|min:1|max:40',
    ];
    $validator = Validator::make($request->all(), $rules, $messages);
    if ($validator->fails()) {
      return response()->json([
        'message' => $validator->messages(),
      ], 400);
    }
    $user = new $model([
      'email'    => $request->email,
      'name'     => $request->name,
      'password' => $request->password,
    ]);
    if ($request->has('tel')) {
      $user->tel = $request->tel;
    }
    $user->scopes = $default_scopes;
    $user->save();
    return (new $resource($user));
  }

  /**
   * Signin
   *
   * @bodyParam  email mail required Admin Email Account Example: wasa@wasateam.com
   * @bodyParam  password string required Example: 123123
   *
   */
  public function signin(Request $request)
  {
    $model        = config('stone.auth.model');
    $active_check = config('stone.auth.active_check');
    $model_name   = config('stone.auth.model_name');
    $request->validate([
      'email'       => 'required|email',
      'password'    => 'required|string',
      'remember_me' => 'boolean',
    ]);
    $snap = $model::where('email', $request->email);
    if ($active_check) {
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
    $tokenResult = $user->createToken('Personal Access Token', $user->scopes);
    $token       = $tokenResult->token;
    if ($request->remember_me) {
      $token->expires_at = Carbon::now()->addWeeks(60);
    }
    $token->save();
    ModelHelper::ws_Log($model, $request, 'signin');
    return response()->json([
      'access_token'  => $tokenResult->accessToken,
      'expires_at'    => Carbon::parse(
        $tokenResult->token->expires_at
      )->toDateTimeString(),
      "{$model_name}" => $user,
    ], 200);
  }

  /**
   * Get User
   *
   * @authenticated
   *
   */
  public function user()
  {
    $resource = config('stone.auth.resource');
    $user     = Auth::user();
    if (!$user) {
      return response()->json([
        'message' => 'cannot find user.',
      ], 401);
    }
    return response()->json([
      'user'   => new $resource($user),
      'scopes' => $user->scopes,
    ], 200);
  }

  /**
   * Signout
   *
   * @authenticated
   *
   */
  public function signout(Request $request)
  {
    ModelHelper::ws_Log(config('stone.auth.model'), $request, 'signout');
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
    $resource = config('stone.auth.resource');
    $rules    = [
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
    if ($request->has('payload')) {
      $user->payload = $request->payload;
    }
    $user->save();
    return (new $resource($user));
  }

  /**
   * Password Update
   *
   * @authenticated
   *
   * @bodyParam password password
   * @bodyParam new_password password
   * @bodyParam new_password_confirmation password
   *
   */
  public function password_update(Request $request)
  {
    $resource = config('stone.auth.resource');
    $messages = [
      'password.min' => 'password too short.',
    ];
    $rules = [
      'password'     => 'required|string|min:6',
      'new_password' => 'required|string|confirmed|min:6',
    ];
    $validator = Validator::make($request->all(), $rules, $messages);
    if ($validator->fails()) {
      return response()->json([
        'message' => $validator->messages(),
      ], 400);
    }
    $user = Auth::user();
    if (!Hash::check($request->password, $user->password)) {
      return response()->json([
        'message' => 'password not correct.',
      ], 401);
    }
    if ($request->new_password !== $request->new_password_confirmation) {
      return response()->json([
        'message' => 'confirmation not match.',
      ], 401);
    }
    $user->password = $request->new_password;
    $user->save();
    return (new $resource($user));
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
    $model_name = config('stone.auth.model_name');
    $user       = Auth::user();
    return StorageHelper::getGoogleUploadSignedUrlByNameAndPath($filename, "{$model_name}/{$user->id}", 'image/png');
  }
}
