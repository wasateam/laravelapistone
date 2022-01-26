<?php

namespace Wasateam\Laravelapistone\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Wasateam\Laravelapistone\Helpers\ModelHelper;

/**
 * @group ShopShipTimeSetting 配送時間設定
 *
 * APIs for shop_ship_time_setting
 *
 * today_shop_order_count 今日訂單配送數量
 * start_time 開始時間
 * end_time 結束時間
 * max_count 配送訂單最大數量
 * type 類別
 *
 * @authenticated
 */
class ShopShipTimeSettingController extends Controller
{
  public $model        = 'Wasateam\Laravelapistone\Models\ShopShipTimeSetting';
  public $name         = 'shop_ship_time_setting';
  public $resource     = 'Wasateam\Laravelapistone\Resources\ShopShipTimeSetting';
  public $input_fields = [
    'start_time',
    'end_time',
    'max_count',
    'type',
  ];
  public $filter_fields = [
    'type',
  ];
  public $belongs_to = [
  ];
  public $belongs_to_many = [
  ];
  public $order_fields = [
    'start_time',
    'end_time',
    'max_count',
  ];

  public function __construct()
  {
  }

  /**
   * Index
   * @queryParam search string No-example
   *
   */
  public function index(Request $request, $id = null)
  {
    return ModelHelper::ws_IndexHandler($this, $request, $id);
  }

  /**
   * Store
   *
   * @bodyParam start_time string No-example
   * @bodyParam end_time string No-example
   * @bodyParam max_count string No-example
   * @bodyParam type string No-example
   */
  public function store(Request $request, $id = null)
  {
    return ModelHelper::ws_StoreHandler($this, $request, $id);
  }

  /**
   * Show
   *
   * @urlParam  shop_ship_time_setting required The ID of shop_ship_time_setting. Example: 1
   */
  public function show(Request $request, $id = null)
  {
    return ModelHelper::ws_ShowHandler($this, $request, $id);
  }

  /**
   * Update
   *
   * @urlParam  shop_ship_time_setting required The ID of shop_ship_time_setting. Example: 1
   * @bodyParam start_time string No-example
   * @bodyParam end_time string No-example
   * @bodyParam max_count string No-example
   * @bodyParam type string No-example
   */
  public function update(Request $request, $id)
  {
    return ModelHelper::ws_UpdateHandler($this, $request, $id);
  }

  /**
   * Delete
   *
   * @urlParam  shop_ship_time_setting required The ID of shop_ship_time_setting. Example: 2
   */
  public function destroy($id)
  {
    return ModelHelper::ws_DestroyHandler($this, $id);
  }
}
