<?php

namespace Wasateam\Laravelapistone\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Wasateam\Laravelapistone\Helpers\ModelHelper;

/**
 * @group ShopOrderShopProductSpec 訂單商品規格
 *
 * ShopOrderShopProductSpec API
 *
 * shop_order_shop_product 綁定之訂單商品
 * cost 成本
 * price 售價
 * discount_price 優惠價格
 * freight 運費
 * shop_product_spec_settings 綁定之商品規格設定
 * shop_product_spec_setting_items 綁定之商品規格設定項目
 * shop_product_spec 綁定之商品規格
 *
 * @authenticated
 */
class ShopOrderShopProductSpecController extends Controller
{
  public $model        = 'Wasateam\Laravelapistone\Models\ShopOrderShopProductSpec';
  public $name         = 'shop_order_shop_product_spec';
  public $resource     = 'Wasateam\Laravelapistone\Resources\ShopOrderShopProductSpec';
  public $input_fields = [
    'cost',
    'price',
    'discount_price',
    'freight',
  ];
  public $filter_fields = [
  ];
  public $belongs_to = [
  ];
  public $filter_belongs_to = [
    'shop_order_shop_product',
    'shop_product_spec',
  ];
  public $belongs_to_many = [
  ];
  public $filter_belongs_to_many = [
    'shop_order_shop_product_spec_settings',
    'shop_order_shop_product_spec_setting_items',
  ];

  public function __construct()
  {
    if (config('stone.mode') == 'cms') {
      $input_fields[]    = 'cost';
      $input_fields[]    = 'price';
      $input_fields[]    = 'discount_price';
      $input_fields[]    = 'freight';
      $belongs_to[]      = 'shop_order_shop_product';
      $belongs_to[]      = 'shop_product_spec';
      $belongs_to_many[] = 'shop_order_shop_product_spec_settings';
      $belongs_to_many[] = 'shop_ordeshop_order_shop_product_spec_setting_itemsr_shop_product';
    }
  }

  /**
   * Index
   * @queryParam shop_order_shop_product ids 訂單商品  No-example 1
   * @queryParam shop_order_shop_product_spec_settings ids 訂單商品規格設定  No-example 1
   * @queryParam shop_ordeshop_order_shop_product_spec_setting_itemsr_shop_product ids 訂單商品規格設定項目  No-example 1
   *
   */
  public function index(Request $request, $id = null)
  {
    if (config('stone.mode') == 'cms') {
      return ModelHelper::ws_IndexHandler($this, $request, $id);
    } else if (config('stone.mode') == 'webapi') {
      return ModelHelper::ws_IndexHandler($this, $request, $id, true);
    }
  }

  /**
   * Store
   *
   * @bodyParam cost int 成本 Example: 200
   * @bodyParam price int 售價 Example: 300
   * @bodyParam discount_price int 優惠價 Example: 280
   * @bodyParam freight string 運費 Example: 10
   * @bodyParam shop_order_shop_product int 訂單商品 Example: 1
   * @bodyParam shop_order_shop_product_spec_settings object 訂單商品規格設定 Example: [1,2,3]
   * @bodyParam shop_order_shop_product_spec_setting_items object 訂單商品規格設定項目 Example: [1,2,3]
   */
  public function store(Request $request, $id = null)
  {
    return ModelHelper::ws_StoreHandler($this, $request, $id);
  }

  /**
   * Show
   *
   * @urlParam  shop_order_shop_product required The ID of shop_order_shop_product. Example: 1
   */
  public function show(Request $request, $id = null)
  {
    return ModelHelper::ws_ShowHandler($this, $request, $id);
  }

  /**
   * Update
   *
   * @urlParam  shop_order_shop_product required The ID of shop_order_shop_product. Example: 1
   * @bodyParam cost int 成本 Example: 200
   * @bodyParam price int 售價 Example: 300
   * @bodyParam discount_price int 優惠價 Example: 280
   * @bodyParam freight string 運費 Example: 10
   * @bodyParam shop_order_shop_product int 訂單商品 Example: 1
   * @bodyParam shop_order_shop_product_spec_settings object 訂單商品規格設定 Example: [1,2,3]
   * @bodyParam shop_order_shop_product_spec_setting_items object 訂單商品規格設定項目 Example: [1,2,3]
   */
  public function update(Request $request, $id)
  {
    return ModelHelper::ws_UpdateHandler($this, $request, $id);
  }

  /**
   * Delete
   *
   * @urlParam  shop_order_shop_product required The ID of shop_order_shop_product. Example: 2
   */
  public function destroy($id)
  {
    return ModelHelper::ws_DestroyHandler($this, $id);
  }

}
