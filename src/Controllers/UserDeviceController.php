<?php

namespace Wasateam\Laravelapistone\Controllers;

use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Wasateam\Laravelapistone\Helpers\ModelHelper;

/**
 * @group 使用者綁定裝置
 *
 * @authenticated
 *
 * type 類型
 * is_diy 是否為自組
 * serial_number 序號
 * brand 品牌
 * product_code 產品流水號
 * country_code 國家代碼
 * status 狀態
 * ~ active 啟用中
 * ~ deactive 非啟用
 */
class UserDeviceController extends Controller
{
  public $model        = 'Wasateam\Laravelapistone\Models\UserDevice';
  public $name         = 'user_device';
  public $resource     = 'Wasateam\Laravelapistone\Resources\UserDevice';
  public $input_fields = [
    'type',
    'is_diy',
    'serial_number',
    'brand',
    'product_code',
    'country_code',
    'status',
  ];
  public $belongs_to = [
    'user',
  ];
  public $filter_belongs_to = [
    'user',
  ];
  public $search_fields = [
    'serial_number',
    'brand',
    'product_code',
  ];
  public $order_fields = [
    'updated_at',
    'created_at',
  ];
  public $uuid = true;

  public function __construct()
  {
    if (config('stone.mode') == 'cms') {
      $this->belongs_to = [
        'user',
      ];
    }
  }

  /**
   * Index
   * @urlParam search string No-example
   *
   */
  public function index(Request $request, $id = null)
  {

    if (config('stone.mode') == 'cms') {
      return ModelHelper::ws_IndexHandler($this, $request, $id);
    } else if (config('stone.mode') == 'webapi') {
      return ModelHelper::ws_IndexHandler($this, $request, $id, false, function ($snap) {
        $user = Auth::user();
        $snap = $snap->where('user_id', $user->id);
        return $snap;
      });
    }
  }

  /**
   * Store
   *
   * @bodyParam serial_number string Example: FK1FA0I0Zg90
   * @bodyParam user string Example: 1
   * @bodyParam type string No-example
   * @bodyParam is_diy string No-example
   * @bodyParam brand string No-example
   * @bodyParam product_code string No-example
   * @bodyParam country_code string No-example
   * @bodyParam status string No-example
   */
  public function store(Request $request, $id = null)
  {
    if (config('stone.mode') == 'cms') {
      return ModelHelper::ws_StoreHandler($this, $request, $id);
    } else if (config('stone.mode') == 'webapi') {
      return ModelHelper::ws_StoreHandler($this, $request, $id, null, function ($model) {
        $user           = Auth::user();
        $model->user_id = $user->id;
      });
    }
  }

  /**
   * Show
   *
   * @urlParam  user_device required The ID of user_device. Example: 1
   */
  public function show(Request $request, $id = null)
  {

    if (config('stone.mode') == 'cms') {
      return ModelHelper::ws_ShowHandler($this, $request, $id);
    } else if (config('stone.mode') == 'webapi') {
      return ModelHelper::ws_ShowHandler($this, $request, $id, function ($snap) {
        $user = Auth::user();
        $snap = $snap->where('user_id', $user->id);
        return $snap;
      });
    }
  }

  /**
   * Update
   *
   * @urlParam  user_device required The ID of user_device. Example: 1
   * @bodyParam type string No-example
   * @bodyParam is_diy string No-example
   * @bodyParam serial_number string Example: FK1FA0I0Zg90
   * @bodyParam user string Example: 1
   * @bodyParam brand string No-example
   * @bodyParam product_code string No-example
   * @bodyParam country_code string No-example
   * @bodyParam status string No-example
   */
  public function update(Request $request, $id)
  {
    return ModelHelper::ws_UpdateHandler($this, $request, $id);
  }

  /**
   * Delete
   *
   * @urlParam  user_device required The ID of user_device. Example: 2
   */
  public function destroy($id)
  {
    if (config('stone.mode') == 'cms') {
      return ModelHelper::ws_DestroyHandler($this, $id);
    }
  }

  /**
   * Register
   * @bodyParam user string Example: 1
   *
   */
  public function register(Request $request)
  {
    try {

      $user                 = Auth::user();
      $limit = 0;
      if (config('stone.user.device.limit')) {
        $limit = config('stone.user.device.limit');
      }

      $active_user_devices_count = $this->model::where('user_id', $user->id)
        ->where('status', 'active')->count();

      if ($active_user_devices_count >= $limit) {
        return response()->json([
          'message' => 'no more device quota.',
        ], 403);
      }

      $uuid                 = Str::uuid();
      $type                 = $request->type;
      $brand                = $request->brand ? $request->brand : 'DIY';
      $is_diy               = $request->is_diy;
      $serial_number        = $request->serial_number ? $request->serial_number : $uuid;
      $product_code         = $is_diy ? $uuid : $request->product_code;
      $model                = new $this->model;
      $model->type          = $type;
      $model->brand         = $brand;
      $model->is_diy        = $is_diy;
      $model->serial_number = $serial_number;
      $model->product_code  = $product_code;
      $model->user_id       = $user->id;
      $model->uuid          = $uuid;
      $model->status        = 'active';
      if (config('stone.user.device.register_before_action')) {
        $model = config('stone.user.device.register_before_action')::device_register_before_action($model, $user);
      }
      $model->save();
      return response()->json([
        'message' => 'registerd.',
      ], 200);
    } catch (\Throwable $th) {
      return response()->json([
        'message' => 'register fail.',
      ], 400);
    }
  }

  /**
   * Get User Binding Status
   * @bodyParam user string Example: 1
   *
   */
  public function get_info_user_binding_status()
  {
    $limit = 0;
    if (config('stone.user.device.limit')) {
      $limit = config('stone.user.device.limit');
    }
    $user                      = Auth::user();
    $active_user_devices_count = $this->model::where('user_id', $user->id)
      ->where('status', 'active')->count();
    return response()->json([
      'limit'                     => $limit,
      'active_user_devices_count' => $active_user_devices_count,
    ], 200);
  }
}