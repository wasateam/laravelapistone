<?php

namespace Wasateam\Laravelapistone\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Wasateam\Laravelapistone\Helpers\ModelHelper;

/**
 * @group ShopProductCoverFrame 活動圖框
 *
 * APIs for shop_product_cover_frame
 *
 * name 活動名稱
 * url 圖框圖片連結
 * start_date 開始時間
 * end_date 結束時間
 * is_active
 * ~ 0 未上架
 * ~ 1 已上架
 * order_type 管別 -> 搭配商品、訂單類別
 * ~ next-day 隔日配
 * ~ pro-order 預購
 *
 * @authenticated
 */
class ShopProductCoverFrameController extends Controller
{
  public $model        = 'Wasateam\Laravelapistone\Models\ShopProductCoverFrame';
  public $name         = 'shop_product_cover_frame';
  public $resource     = 'Wasateam\Laravelapistone\Resources\ShopProductCoverFrame';
  public $input_fields = [
    'name',
    'url',
    'start_date',
    'end_date',
    'is_active',
    'order_type',
  ];
  public $filter_fields = [
    'order_type',
  ];
  public $uuid = false;

  public function __construct()
  {
    if (config('stone.shop.uuid')) {
      $this->uuid = true;
    }
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
   * @bodyParam name string Example:name
   * @bodyParam url string Example:""
   * @bodyParam start_date date Example:2022-02-10
   * @bodyParam end_date date Example:2022-05-10
   * @bodyParam is_active boolean Example:1
   * @bodyParam order_type string Example:next-day
   */
  public function store(Request $request, $id = null)
  {
    return ModelHelper::ws_StoreHandler($this, $request, $id);
  }

  /**
   * Show
   *
   * @urlParam  shop_product_cover_frame required The ID of shop_product_cover_frame. Example: 1
   */
  public function show(Request $request, $id = null)
  {
    return ModelHelper::ws_ShowHandler($this, $request, $id);
  }

  /**
   * Update
   *
   * @urlParam  shop_product_cover_frame required The ID of shop_product_cover_frame. Example: 1
   * @bodyParam name string Example:name
   * @bodyParam url string Example:""
   * @bodyParam start_date date Example:2022-02-10
   * @bodyParam end_date date Example:2022-05-10
   * @bodyParam is_active boolean Example:1
   * @bodyParam order_type string Example:next-day
   */
  public function update(Request $request, $id)
  {
    return ModelHelper::ws_UpdateHandler($this, $request, $id);
  }

  /**
   * Delete
   *
   * @urlParam  shop_product_cover_frame required The ID of shop_product_cover_frame. Example: 2
   */
  public function destroy($id)
  {
    return ModelHelper::ws_DestroyHandler($this, $id);
  }
}
