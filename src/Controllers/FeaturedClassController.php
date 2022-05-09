<?php

namespace Wasateam\Laravelapistone\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Wasateam\Laravelapistone\Helpers\ModelHelper;

/**
 * @group FeaturedClass 精選分類
 *
 * @authenticated
 *
 * name 名稱
 * icon
 * sq
 * is_outstanding 是否為精選中的精選
 * order_type 訂單類型
 * is_active 是否顯示於前台
 */
class FeaturedClassController extends Controller
{
  public $model        = 'Wasateam\Laravelapistone\Models\FeaturedClass';
  public $name         = 'featured_class';
  public $resource     = 'Wasateam\Laravelapistone\Resources\FeaturedClass';
  public $input_fields = [
    'name',
    'icon',
    'sq',
    'is_outstanding',
    'order_type',
    'is_active',
  ];
  public $filter_fields = [
    'order_type',
    'is_outstanding',
    'is_active',
  ];
  public $order_fields = [
    'sq',
  ];
  public $order_by  = "sq";
  public $order_way = "asc";
  public $uuid      = false;

  public function __construct()
  {
    if (config('stone.shop.uuid')) {
      $this->uuid = true;
    }
    if (config('stone.mode') == 'cms') {
      $this->belongs_to_many[] = "shop_products";
    }
  }

  /**
   * Index
   * @queryParam search string No-example
   * @queryParam order_type string No-example pre-order,next-day
   * @queryParam is_outstanding boolean Example:1
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
   * @bodyParam name string Example:name
   * @bodyParam icon string No-example
   * @bodyParam sq string Example:123
   * @bodyParam is_outstanding boolean Example:1
   * @bodyParam order_type string Example:pre-order
   */
  public function store(Request $request, $id = null)
  {
    return ModelHelper::ws_StoreHandler($this, $request, $id);
  }

  /**
   * Show
   *
   * @urlParam  featured_class required The ID of featured_class. Example: 1
   */
  public function show(Request $request, $id = null)
  {
    return ModelHelper::ws_ShowHandler($this, $request, $id);
  }

  /**
   * Update
   *
   * @urlParam  featured_class required The ID of featured_class. Example: 1
   * @bodyParam name string Example:name
   * @bodyParam icon string No-example
   * @bodyParam sq string Example:123
   * @bodyParam is_outstanding boolean Example:1
   * @bodyParam order_type string Example:pre-order
   */
  public function update(Request $request, $id)
  {
    return ModelHelper::ws_UpdateHandler($this, $request, $id);
  }

  /**
   * Delete
   *
   * @urlParam  featured_class required The ID of featured_class. Example: 2
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

  /**
   * Shop Product Order Get
   *
   */
  public function shop_product_order_get($id)
  {
    return ModelHelper::ws_BelongsToManyOrderGetHandler($id, $this, 'shop_products_is_active', 'Wasateam\Laravelapistone\Resources\ShopProduct_R_Order_FeaturedClass');
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
