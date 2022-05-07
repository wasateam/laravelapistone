<?php

namespace Wasateam\Laravelapistone\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Wasateam\Laravelapistone\Helpers\ModelHelper;

/**
 * @group ShopSubclass 商品子分類
 *
 * APIs for shop_subclass
 *
 * name 分類名稱
 * sq 排序
 * type 分類
 * icon 圖片
 * is_active 是否顯示於前台
 *
 * @authenticated
 */
class ShopSubclassController extends Controller
{
  public $model        = 'Wasateam\Laravelapistone\Models\ShopSubclass';
  public $name         = 'shop_subclass';
  public $resource     = 'Wasateam\Laravelapistone\Resources\ShopSubclass';
  public $input_fields = [
    'name',
    'sq',
    'type',
    'icon',
    'is_active',
  ];
  public $belongs_to = [
    'shop_class',
  ];
  public $filter_belongs_to = [
    'shop_class',
  ];
  public $filter_fields = [
    'order_type',
    'is_active',
  ];
  public $order_fields = [
    'sq',
  ];
  public $order_by  = 'sq';
  public $order_way = 'desc';
  public $uuid      = false;

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
   * @bodyParam name string No-example
   * @bodyParam sq string No-example
   * @bodyParam type string No-example
   * @bodyParam shop_class id No-example
   */
  public function store(Request $request, $id = null)
  {
    return ModelHelper::ws_StoreHandler($this, $request, $id);
  }

  /**
   * Show
   *
   * @urlParam  shop_subclass required The ID of shop_subclass. Example: 1
   */
  public function show(Request $request, $id = null)
  {
    return ModelHelper::ws_ShowHandler($this, $request, $id);
  }

  /**
   * Update
   *
   * @urlParam  shop_subclass required The ID of shop_subclass. Example: 1
   * @bodyParam name string No-example
   * @bodyParam sq string No-example
   * @bodyParam type string No-example
   * @bodyParam shop_class id No-example
   */
  public function update(Request $request, $id)
  {
    return ModelHelper::ws_UpdateHandler($this, $request, $id);
  }

  /**
   * Delete
   *
   * @urlParam  shop_subclass required The ID of shop_subclass. Example: 2
   */
  public function destroy($id)
  {
    return ModelHelper::ws_DestroyHandler($this, $id);
  }

  /**
   * Shop Product Order Get
   *
   */
  public function shop_product_order_get($id)
  {
    return ModelHelper::ws_BelongsToManyOrderGetHandler($id, $this, 'shop_products', 'Wasateam\Laravelapistone\Resources\ShopProduct_R_Order_ShopSubclass');
  }

  /**
   * Shop Product Order Patch
   *
   */
  public function shop_product_order_patch($id, Request $request)
  {
    return ModelHelper::ws_BelongsToManyOrderPatchHandler($id, $this, 'shop_products', $request, 'sq');
  }
}
