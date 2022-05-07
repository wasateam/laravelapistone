<?php

namespace Wasateam\Laravelapistone\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Wasateam\Laravelapistone\Helpers\ModelHelper;

/**
 * @group ShopClass 商品分類
 * @authenticated
 *
 * name 分類名稱
 * sq 排序設定值
 * ~ 數值越低、排序越前
 * type 類型
 * icon Icon Url
 * order_type 訂單類型
 * ~ next-day 隔日配
 * ~ pro-order 預購
 * is_active 是否顯示於前台
 */
class ShopClassController extends Controller
{
  public $model              = 'Wasateam\Laravelapistone\Models\ShopClass';
  public $name               = 'shop_class';
  public $resource           = 'Wasateam\Laravelapistone\Resources\ShopClass';
  public $resource_for_order = 'Wasateam\Laravelapistone\Resources\ShopClass_R_Order';
  public $input_fields       = [
    'name',
    'sq',
    'type',
    'icon',
    'order_type',
    'is_active',
  ];
  public $search_fields = [
    'name',
  ];
  public $filter_fields = [
    'order_type',
    'is_active',
  ];
  public $belongs_to = [
  ];
  public $order_fields = [
    'sq',
  ];
  public $order_layers_setting = [
    [
      'model' => 'Wasateam\Laravelapistone\Models\ShopSubclass',
      'key'   => 'shop_subclasses',
    ],
  ];
  public $order_by  = 'sq';
  public $order_way = 'asc';
  public $uuid      = false;

  public function __construct()
  {
    if (config('stone.shop.uuid')) {
      $this->uuid = true;
    }
  }

  /**
   * Index
   * @queryParam search string 搜尋字串 No-example
   * @queryParam order_type string 訂單類型  No-example next-day,pre-order
   *
   */
  public function index(Request $request, $id = null)
  {
    if (config('stone.mode') == 'cms') {
      return ModelHelper::ws_IndexHandler($this, $request, $id);
    } else if (config('stone.mode') == 'webapi') {
      return ModelHelper::ws_IndexHandler($this, $request, $id, true, function ($snap) {
        $snap = $snap->where('is_active', 1);
        return $snap;
      });
    }
  }

  /**
   * Store
   *
   * @bodyParam name string 名稱 No-example
   * @bodyParam sq string 順序設定 No-example
   * @bodyParam type string 類型(current現貨,pre_order預購) No-example
   */
  public function store(Request $request, $id = null)
  {
    return ModelHelper::ws_StoreHandler($this, $request, $id);
  }

  /**
   * Show
   *
   * @urlParam  shop_class required The ID of shop_class. Example: 1
   */
  public function show(Request $request, $id = null)
  {
    return ModelHelper::ws_ShowHandler($this, $request, $id);
  }

  /**
   * Update
   *
   * @urlParam  shop_class required The ID of shop_class. Example: 1
   * @bodyParam name string 名稱 No-example
   * @bodyParam sq string 順序設定 No-example
   * @bodyParam type string 類型(current現貨,pre_order預購) No-example
   */
  public function update(Request $request, $id)
  {
    return ModelHelper::ws_UpdateHandler($this, $request, $id);
  }

  /**
   * Delete
   *
   * @urlParam  shop_class required The ID of shop_class. Example: 2
   */
  public function destroy($id)
  {
    return ModelHelper::ws_DestroyHandler($this, $id);
  }

  /**
   * Order Get
   *
   */
  public function order_get(Request $request)
  {
    return ModelHelper::ws_OrderGetHandler($this, $request);
  }

  /**
   * Order Patch
   *
   */
  public function order_patch(Request $request)
  {
    return ModelHelper::ws_OrderPatchHandler($this, $request);
  }
}
