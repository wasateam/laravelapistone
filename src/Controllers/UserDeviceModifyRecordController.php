<?php

namespace Wasateam\Laravelapistone\Controllers;

use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Wasateam\Laravelapistone\Helpers\ModelHelper;

/**
 * @group UserDeviceModifyRecord 使用者綁定裝置
 *
 * @authenticated
 *
 * type 類型
 * action 動作
 * ~ change 更新
 * ~ delete 移除
 * ~ active 啟用
 * ~ inactive 停用
 * payload
 * user_device 使用者裝置
 * user 使用者
 */
class UserDeviceModifyRecordController extends Controller
{
  public $model        = 'Wasateam\Laravelapistone\Models\UserDeviceModifyRecord';
  public $name         = 'user_device_modify_record';
  public $resource     = 'Wasateam\Laravelapistone\Resources\UserDeviceModifyRecord';
  public $input_fields = [
    'action',
    'remark',
    'payload',
  ];
  public $filter_fields = [
    'action',
  ];
  public $belongs_to = [
    'user_device',
    'user',
  ];
  public $filter_belongs_to = [
    'user_device',
    'user',
  ];
  public $search_fields = [
  ];
  public $order_fields = [
    'updated_at',
    'created_at',
  ];
  public $uuid = true;

  public function __construct()
  {
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
   * @bodyParam action string Example: change
   * @bodyParam remark string No-example
   * @bodyParam payload  string No-example
   * @bodyParam user_device id No-example
   * @bodyParam user id No-example
   *
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
   * @urlParam  user_device_modify_record required The ID of user_device_modify_record. Example: 1
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
   * @urlParam  user_device_modify_record required The ID of user_device_modify_record. Example: 1
   * @bodyParam action string Example: change
   * @bodyParam remark string No-example
   * @bodyParam payload  string No-example
   * @bodyParam user_device id No-example
   * @bodyParam user id No-example
   */
  public function update(Request $request, $id)
  {
    return ModelHelper::ws_UpdateHandler($this, $request, $id);
  }

  /**
   * Delete
   *
   * @urlParam  user_device_modify_record required The ID of user_device_modify_record. Example: 2
   */
  public function destroy($id)
  {
    if (config('stone.mode') == 'cms') {
      return ModelHelper::ws_DestroyHandler($this, $id);
    }
  }
}
