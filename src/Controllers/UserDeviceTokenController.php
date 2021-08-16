<?php

namespace Wasateam\Laravelapistone\Controllers;

use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Http\Request;
use Wasateam\Laravelapistone\Helpers\ModelHelper;

/**
 * @group UserDeviceToken
 *
 * @authenticated
 *
 * APIs for user_device_token
 */
class UserDeviceTokenController extends Controller
{
  public $model        = 'Wasateam\Laravelapistone\Models\UserDeviceToken';
  public $name         = 'user_device_token';
  public $resource     = 'Wasateam\Laravelapistone\Resources\UserDeviceToken';
  public $input_fields = [
    'device_token',
    'is_active',
    'remark',
  ];
  public $belongs_to = [
    'user',
  ];
  public $filter_belongs_to = [
    'user',
  ];
  public $order_fields      = [
    'updated_at',
    'created_at',
  ];
  public $order_belongs_to = [
    'user',
  ];

  /**
   * Index
   *
   */
  public function index(Request $request, $id = null)
  {
    return ModelHelper::ws_IndexHandler($this, $request, $id);
  }

  /**
   * Store
   *
   * @bodyParam  device_token string Example: 1
   * @bodyParam  is_active boolean Example: 1
   * @bodyParam  remark string No-example
   * @bodyParam  user string Example: 1
   */
  public function store(Request $request, $id = null)
  {
    return ModelHelper::ws_StoreHandler($this, $request, $id);
  }

  /**
   * Show
   *
   * @urlParam  user_device_token required The ID of user_device_token. Example: 1
   */
  public function show(Request $request, $id = null)
  {
    return ModelHelper::ws_ShowHandler($this, $request, $id);
  }

  /**
   * Update
   *
   * @urlParam  user_device_token required The ID of user_device_token. Example: 1
   * @bodyParam  device_token string Example: 1
   * @bodyParam  is_active boolean Example: 1
   * @bodyParam  remark string No-example
   * @bodyParam  user string Example: 1
   */
  public function update(Request $request, $id)
  {
    return ModelHelper::ws_UpdateHandler($this, $request, $id);
  }

  /**
   * Delete
   *
   * @urlParam  user_device_token required The ID of user_device_token. Example: 2
   */
  public function destroy($id)
  {
    return ModelHelper::ws_DestroyHandler($this, $id);
  }

  /**
   * Active
   *
   * @bodyParam  device_token string required
   */
  public function active(Request $request)
  {
    if (!$request->filled('device_token')) {
      return response()->json([
        'message' => 'device_token required.',
      ], 400);
    }
    $user    = Auth::user();
    $setting = ModelHelper::getSetting($this);
    $model   = $setting->model::where('user_id', $user->id)
      ->where('device_token', $request->device_token)->first();
    if (!$model) {
      $model               = new $setting->model;
      $model->user_id      = $user->id;
      $model->device_token = $request->device_token;
    }
    $model->is_active = true;
    $model->save();
    return new $setting->resource($model);
  }

  /**
   * Deactive
   *
   * @bodyParam  device_token string required
   */
  public function deactive(Request $request)
  {
    if (!$request->filled('device_token')) {
      return response()->json([
        'message' => 'device_token required.',
      ], 400);
    }
    $user    = Auth::user();
    $setting = ModelHelper::getSetting($this);
    $model   = $setting->model::where('user_id', $user->id)
      ->where('device_token', $request->device_token)->first();
    if ($model) {
      $model->is_active = false;
      $model->save();
    }
    return response()->json([
      'message' => 'deactived.',
    ], 400);
  }
}
