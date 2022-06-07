<?php

namespace Wasateam\Laravelapistone\Modules\UserPosition\App\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Wasateam\Laravelapistone\Helpers\ModelHelper;
use Wasateam\Laravelapistone\Models\UserPosition;

/**
 * @group UserPosition 使用者位置
 *
 *
 * @authenticated
 *
 * lat 緯度
 * lng 經度
 * user 使用者
 *
 */
class UserPositionController extends Controller
{
  public $model        = 'Wasateam\Laravelapistone\Modules\UserPosition\App\Models\UserPosition';
  public $name         = 'user_position';
  public $resource     = 'Wasateam\Laravelapistone\Modules\UserPosition\App\Http\Resources\UserPosition';
  public $input_fields = [
    'lat',
    'lng',
  ];
  public $belongs_to = [
    'user',
  ];
  public $order_fields = [
    'updated_at',
    'created_at',
  ];
  public $filter_time_fields = [
    'birthday',
  ];

  public function __construct()
  {
    if (config('stone.country_code')) {
    }
  }

  /**
   * Index
   * @queryParam search string No-example
   *
   */
  public function index(Request $request, $id = null)
  {
    return ModelHelper::ws_IndexHandler($this, $request, $id, true);
  }

  /**
   * Show
   *
   * @urlParam  user_position required The ID of user_position. Example: 1
   */
  public function show(Request $request, $id = null)
  {
    return ModelHelper::ws_ShowHandler($this, $request, $id);
  }

  /**
   * Store User My
   *
   * lat 緯度 float Example: 25.055190
   * lng 經度 float Example: 121.532771
   */
  public function store_user_my(Request $request, $id = null)
  {
    return ModelHelper::ws_StoreHandler($this, $request, $id, function ($model) {
      $model->user_id = \Auth::user()->id;
      $model->save();
    });
  }

  /**
   * Store User My
   *
   * lat 緯度 float Example: 25.055190
   * lng 經度 float Example: 121.532771
   */
  public function show_user_my_recent()
  {
    $user = Auth::user();
    return new \Wasateam\Laravelapistone\Modules\UserPosition\App\Http\Resources\UserPosition_R1($user->user_position_recent);
  }
}
