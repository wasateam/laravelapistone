<?php

namespace Wasateam\Laravelapistone\Controllers;

use App\Http\Controllers\Controller;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Validator;
use Wasateam\Laravelapistone\Helpers\AuthHelper;
use Wasateam\Laravelapistone\Helpers\EmailHelper;
use Wasateam\Laravelapistone\Helpers\ModelHelper;
use Wasateam\Laravelapistone\Helpers\OtpHelper;
use Wasateam\Laravelapistone\Helpers\RequestHelper;
use Wasateam\Laravelapistone\Helpers\SmsHelper;
use Wasateam\Laravelapistone\Helpers\StorageHelper;
use Wasateam\Laravelapistone\Helpers\UserHelper;
use Wasateam\Laravelapistone\Models\Admin;
use Wasateam\Laravelapistone\Models\User;
use Wasateam\Laravelapistone\Modules\AppDeveloper\App\Models\AppDeveloper;

/**
 * @group @Auth
 *
 * @authenticated
 *
 * email 信箱
 * mobile 註冊用手機 (不同於 tel)
 * mobile_country_code (手機國碼，可透過)
 *
 * APIs for auth
 */
class AuthController extends Controller
{
  /**
   * Signup
   *
   * @bodyParam  email mail required Auth Email Account Example: wasa@wasateam.com
   * @bodyParam  password string required Example: 123123
   * @bodyParam  password_confirmation string required Check Password match  Example: 123123
   * @bodyParam  name string User Name  Example: wasa
   * @bodyParam  tel string
   * @bodyParam  birthday date
   * @response
   * {
   * "data": {
   * "id": 2,
   * "name": "wasa",
   * "email": "admin@wasateam.com",
   * "created_at": "2020-07-11T15:06:43.000000Z",
   * "updated_at": "2020-07-11T15:06:43.000000Z"
   * }
   * }
   *
   */
  public function signup(Request $request)
  {
    $model          = config('stone.auth.model');
    $model_name     = config('stone.auth.model_name');
    $resource       = config('stone.auth.resource');
    $default_scopes = config('stone.auth.default_scopes');
    $messages       = [
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
      ], 401);
    }
    $user = new $model([
      'email'    => $request->email,
      'name'     => $request->name,
      'password' => $request->password,
    ]);
    if ($request->has('tel')) {
      $user->tel = $request->tel;
    }
    if ($request->has('birthday')) {
      $user->birthday = $request->birthday;
    }
    if ($request->has('gender')) {
      $user->gender = $request->gender;
    }
    if ($request->has('carrier_email')) {
      $user->carrier_email = $request->carrier_email;
    }
    if ($request->has('carrier_phone')) {
      $user->carrier_phone = $request->carrier_phone;
    }
    if ($request->has('carrier_certificate')) {
      $user->carrier_certificate = $request->carrier_certificate;
    }
    if (config('stone.auth.active_check')) {
      $user->is_active = 1;
    }
    if (config('stone.auth.uuid')) {
      $user->uuid = Str::uuid();
    }
    $user->scopes = $default_scopes;
    $user->save();
    if (config('stone.mode') == 'webapi') {
      if (config('stone.user.invite')) {
        $user = UserHelper::generateInviteNo($user, 'Wasateam\Laravelapistone\Models\User');
      }
    }
    if (config('stone.auth.customer_id')) {
      $user->customer_id = AuthHelper::getCustomerId($model, config('stone.auth.customer_id'));
      $user->save();
    }
    if (config('stone.auth.signup_complete_action')) {
      config('stone.auth.signup_complete_action')::signup_complete_action($user);
    }
    if (config('stone.auth.verify')) {
      if (config('stone.auth.verify.email')) {
        $url = AuthHelper::getEmailVerifyUrl($user);
        EmailHelper::email_verify_request($url, $user->email);
      }
    }
    return new $resource($user);
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
   * "email": "admin@wasateam.com",
   * "email_verified_at": null
   * }
   * }
   *
   */
  public function signin(Request $request)
  {
    $model        = config('stone.auth.model');
    $model_name   = config('stone.auth.model_name');
    $active_check = config('stone.auth.active_check');
    $this->name   = 'auth';
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
      throw new \Wasateam\Laravelapistone\Exceptions\AuthException();
    }
    if (!Hash::check($request->password, $user->password)) {
      throw new \Wasateam\Laravelapistone\Exceptions\AuthException();
    }
    $tokenResult = $user->createToken('Personal Access Token', AuthHelper::getUserScopes($user));
    $token       = $tokenResult->token;
    if ($request->remember_me) {
      $token->expires_at = Carbon::now()->addWeeks(60);
    }
    $token->save();
    ModelHelper::ws_Log($model, $this, 'signin', $user);
    return response()->json([
      'access_token'  => $tokenResult->accessToken,
      'expires_at'    => Carbon::parse(
        $tokenResult->token->expires_at
      )->toDateTimeString(),
      "{$model_name}" => $user,
    ], 200);
  }

  /**
   * Mobile Get OTP
   *
   * 若無此使用者會新增帳號，但無論如何，若回傳 200 則會發送驗證簡訊，再導至驗證碼輸入頁面
   *
   * @bodyParam mobile string Example: 555666555
   * @bodyParam mobile_country_code string Example: 886
   *
   */
  public function mobile_get_otp(Request $request)
  {
    $model          = config('stone.auth.model');
    $model_name     = config('stone.auth.model_name');
    $active_check   = config('stone.auth.active_check');
    $default_scopes = config('stone.auth.default_scopes');
    RequestHelper::requestValidate(
      $request,
      [
        'mobile'              => "required|integer",
        'mobile_country_code' => 'required|integer',
      ]
    );

    $snap = $model::where('mobile', $request->mobile)
      ->where('mobile_country_code', $request->mobile_country_code);
    if ($active_check) {
      $snap = $snap->where('is_active', 1);
    }
    $user = $snap->first();
    if (!$user) {
      $user                      = new $model();
      $user->mobile              = $request->mobile;
      $user->mobile_country_code = $request->mobile_country_code;
      if (config('stone.auth.active_check')) {
        $user->is_active = 1;
      }
      if (config('stone.auth.uuid')) {
        $user->uuid = Str::uuid();
      }
      $user->scopes = $default_scopes;
      $user->save();
      if (config('stone.mode') == 'webapi') {
        if (config('stone.user.invite')) {
          $user = UserHelper::generateInviteNo($user, 'Wasateam\Laravelapistone\Models\User');
        }
      }
      if (config('stone.auth.customer_id')) {
        $user->customer_id = AuthHelper::getCustomerId($model, config('stone.auth.customer_id'));
        $user->save();
      }
      if (config('stone.auth.signup_complete_action')) {
        config('stone.auth.signup_complete_action')::signup_complete_action($user);
      }
    }

    if (config('stone.app_developer')) {
      $app_developer = AppDeveloper::where('mobile', $request->mobile)
        ->where('mobile_country_code', $request->mobile_country_code)
        ->first();
      if ($app_developer) {
        return response()->json([
          'message' => 'otp sent.',
        ]);
      }
    }

    $otp = OtpHelper::getAuthOtp($user->id);

    if (config('stone.auth.mobile.otp.types.sms')) {
      SmsHelper::sendAuthOtp($otp, $user);
    }

    return response()->json([
      'message' => 'otp sent.',
    ]);
  }

  /**
   * Signin Mobile
   *
   * OTP 在簡訊串接完成前都用 556655
   *
   *
   * @bodyParam mobile string Example: 555666555
   * @bodyParam mobile_country_code string Example: 886
   * @bodyParam otp string Example: 556655
   *
   */
  public function signin_mobile(Request $request)
  {
    $model          = config('stone.auth.model');
    $model_name     = config('stone.auth.model_name');
    $active_check   = config('stone.auth.active_check');
    $default_scopes = config('stone.auth.default_scopes');
    $this->name     = 'auth';
    RequestHelper::requestValidate(
      $request,
      [
        'mobile'              => "required|integer",
        'mobile_country_code' => 'required|integer',
        'otp'                 => 'required|digits:6',
      ]
    );
    $snap = $model::where('mobile', $request->mobile)
      ->where('mobile_country_code', $request->mobile_country_code);
    if ($active_check) {
      $snap = $snap->where('is_active', 1);
    }
    $user = $snap->first();
    if (!$user) {
      throw new \Wasateam\Laravelapistone\Exceptions\AuthException();
    }

    $check = 0;

    if (config('stone.app_developer')) {
      $app_developer = AppDeveloper::where('mobile', $request->mobile)
        ->where('mobile_country_code', $request->mobile_country_code)
        ->where('otp', $request->otp)
        ->first();
      if ($app_developer) {
        $check = 1;
      }
    }

    if (OtpHelper::checkOtp($user->id, $request->otp)) {
      $check = 1;
    }
    
    if (!$check) {
      throw new \Wasateam\Laravelapistone\Exceptions\GeneralException('otp not match or expired');
    }

    $tokenResult = $user->createToken('Personal Access Token', AuthHelper::getUserScopes($user));
    $token       = $tokenResult->token;
    if ($request->remember_me) {
      $token->expires_at = Carbon::now()->addWeeks(60);
    }
    $token->save();
    ModelHelper::ws_Log($model, $this, 'signin', $user);
    return response()->json([
      'access_token'  => $tokenResult->accessToken,
      'expires_at'    => Carbon::parse(
        $tokenResult->token->expires_at
      )->toDateTimeString(),
      "{$model_name}" => $user,
    ], 200);
  }

  /**
   * Get User 取得當前使用者資訊
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
    $resource   = config('stone.auth.resource');
    $model_name = config('stone.auth.model_name');
    $user       = Auth::user();
    if (!$user) {
      return response()->json([
        'message' => 'cannot find user.',
      ], 401);
    }
    return response()->json([
      'user'   => new $resource($user),
      'scopes' => AuthHelper::getUserScopes($user),
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
    $this->name = 'auth';
    ModelHelper::ws_Log(config('stone.auth.model'), $this, 'signout');
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
    $user = Auth::user();
    if (!$user) {
      return response()->json([
        'message' => 'cannot find user.',
      ], 401);
    }

    $resource = config('stone.auth.resource');
    $rules    = [
      'password' => 'string|min:6',
      'name'     => 'string|min:1|max:40',
      'tel'      => 'string|regex:/^[0-9]+$/',
      'email'    => [
        'required',
        Rule::unique('users')->ignore($user->id),
      ],
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
    if ($request->has('email') && $user->email != $request->email) {
      $user->email             = $request->email;
      $user->email_verified_at = null;
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
    if ($request->has('birthday')) {
      $user->birthday = $request->birthday;
    }
    if ($request->has('description')) {
      $user->description = $request->description;
    }
    if ($request->has('gender')) {
      $user->gender = $request->gender;
    }
    if ($request->has('carrier_email')) {
      $user->carrier_email = $request->carrier_email;
    }
    if ($request->has('carrier_phone')) {
      $user->carrier_phone = $request->carrier_phone;
    }
    if ($request->has('carrier_certificate')) {
      $user->carrier_certificate = $request->carrier_certificate;
    }
    if (config('stone.user.update_before_action')) {
      config('stone.user.update_before_action')::update_before_action($user);
    }
    $user->save();
    return response()->json([
      'user' => new $resource($user),
    ], 200);
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
    $this->model      = config('stone.auth.model');
    $this->model_name = config('stone.auth.model_name');
    $this->name       = config('stone.auth.model_name');
    $this->resource   = config('stone.auth.resource');
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
   * Forget Password Request
   *
   * @authenticated
   *
   * @bodyParam email string Example: wasalearn@gmail.com
   *
   */
  public function forget_password_request(Request $request)
  {
    $model      = config('stone.auth.model');
    $model_name = config('stone.auth.model_name');
    $resource   = config('stone.auth.resource');

    $user = $model::where('email', $request->email)->first();
    if (!$user) {
      return response()->json([
        'message' => 'find no user.',
      ], 400);
    }

    $url = AuthHelper::getPasswordResetUrl($user);
    EmailHelper::password_reset_request($url, $user->email);

    return response()->json([
      'message' => 'mail sent.',
    ], 200);
  }

  /**
   * Forget Password Patch
   *
   * @authenticated
   *
   * @bodyParam password Example:123123
   *
   */
  public function forget_password_patch($user_id, Request $request)
  {
    $model    = config('stone.auth.model');
    $messages = [
      'password.min' => 'password too short.',
    ];
    $rules = [
      'password' => 'required|string|confirmed|min:6',
    ];
    $validator = Validator::make($request->all(), $rules, $messages);
    if ($validator->fails()) {
      return response()->json([
        'message' => $validator->messages(),
      ], 400);
    }
    $user           = $model::find($user_id);
    $user->password = $request->password;
    $user->save();
    $tokenResult = $user->createToken('Personal Access Token', AuthHelper::getUserScopes($user));
    return response()->json([
      'message' => 'password reset.',
      'email'   => $user->email,
      'token'   => $tokenResult->accessToken,
    ], 200);
  }

  /**
   * Email Verify
   *
   * @queryParam expires int required Unix time format Example: 1645007253
   * @queryParam signature int required Signature from verifying mail Example: 8e30b1e398646a1c153b620cfd4d31e7e4fdd813201a4eeb71f34c59069add4c
   *
   */
  public function email_verify($user_id)
  {
    $model                   = config('stone.auth.model');
    $model_name              = config('stone.auth.model_name');
    $user                    = $model::find($user_id);
    $user->email_verified_at = Carbon::now();
    $user->save();

    $tokenResult = $user->createToken('Personal Access Token', AuthHelper::getUserScopes($user));
    $token       = $tokenResult->token;
    ModelHelper::ws_Log($user, $this, 'email_verify', $user, 'user');

    return response()->json([
      'access_token'  => $tokenResult->accessToken,
      'expires_at'    => Carbon::parse(
        $tokenResult->token->expires_at
      )->toDateTimeString(),
      "{$model_name}" => $user,
      'message'       => 'user email verified.',
    ], 200);
  }

  /**
   * Email Verify Resend
   *
   * @bodyParam email No-example
   *
   */
  public function email_verify_resend(Request $request)
  {
    $rules = [
      'email' => "required|string|email",
    ];
    $validator = Validator::make($request->all(), $rules);
    if ($validator->fails()) {
      return response()->json([
        'message' => $validator->messages(),
      ], 400);
    }

    $model = config('stone.auth.model');

    $user = $model::where('email', $request->email)->first();

    if ($user->is_verified) {
      return response()->json([
        'message' => 'email has been verified.',
      ], 400);
    }

    $url = AuthHelper::getEmailVerifyUrl($user);
    EmailHelper::email_verify_request($url, $user->email);
    return response()->json([
      'message' => 'verify mail sent.',
    ], 200);
  }

}
