<?php

namespace Wasateam\Laravelapistone\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Wasateam\Laravelapistone\Helpers\ModelHelper;

/**
 * @group ShopProductSpec 產品規格
 *
 * ShopProductSpec API
 *
 * shop_product 綁定之商品
 * no 編號
 * start_at 上架時間
 * end_at 下架時間
 * cost 成本
 * price 售價
 * discount_price 優惠價格
 * freight 運費
 * stock_count 庫存
 * stock_alert_count 庫存預警
 * storage_space 儲位
 * max_buyable_count 最大可購買數量
 * shop_product_spec_settings 綁定之商品規格設定
 * shop_product_spec_setting_items 綁定之商品規格設定項目
 *
 * @authenticated
 */
class ShopProductSpecController extends Controller
{
  public $model        = 'Wasateam\Laravelapistone\Models\ShopProductSpec';
  public $name         = 'shop_product_spec';
  public $resource     = 'Wasateam\Laravelapistone\Resources\ShopProductSpec';
  public $input_fields = [
    'no',
    'start_at',
    'end_at',
    'cost',
    'price',
    'discount_price',
    'freight',
    'stock_count',
    'stock_alert_count',
    'storage_space',
    'max_buyable_count',
  ];
  public $filter_fields = [
  ];
  public $search_fields = ['no'];
  public $belongs_to    = [
    'shop_product',
  ];
  public $filter_belongs_to = [
    'shop_product',
  ];
  public $belongs_to_many = [
    'shop_product_spec_settings',
    'shop_product_spec_setting_items',
  ];
  public $filter_belongs_to_many = [
    'shop_product_spec_settings',
    'shop_product_spec_setting_items',
  ];

  public function __construct()
  {
  }

  /**
   * Index
   * @queryParam shop_product ids 商品  No-example 1
   * @queryParam shop_product_spec_settings ids 商品  No-example 1
   * @queryParam shop_product_spec_setting_items ids 商品  No-example 1
   * @queryParam search ids 搜尋  No-example no
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
   * @bodyParam no string 編號 Example: SexyOrange
   * @bodyParam cost int 成本 Example: 200
   * @bodyParam price int 售價 Example: 300
   * @bodyParam discount_price int 優惠價 Example: 280
   * @bodyParam start_at datetime 上架時間 Example: 2021-10-10
   * @bodyParam end_at datetime 下架時間 Example: 2021-10-20
   * @bodyParam freight string 運費 Example: 10
   * @bodyParam max_buyable_count string 最大可購買數量 Example: 10
   * @bodyParam storage_space string 商品儲位 Example: AA
   * @bodyParam stock_count string 庫存數量 Example: 100
   * @bodyParam stock_alert_count string 庫存預警數量 Example: 10
   * @bodyParam shop_product int 商品 Example: 1
   * @bodyParam shop_product_spec_settings object 商品規格設定 Example: [1,2,3]
   * @bodyParam shop_product_spec_setting_items object 商品規格設定項目 Example: [1,2,3]
   */
  public function store(Request $request, $id = null)
  {
    return ModelHelper::ws_StoreHandler($this, $request, $id);
  }

  /**
   * Show
   *
   * @urlParam  shop_product_spec required The ID of shop_product_spec. Example: 1
   */
  public function show(Request $request, $id = null)
  {
    return ModelHelper::ws_ShowHandler($this, $request, $id);
  }

  /**
   * Update
   *
   * @urlParam  shop_product_spec required The ID of shop_product_spec. Example: 1
   * @bodyParam no string 編號 Example: SexyOrange
   * @bodyParam cost int 成本 Example: 200
   * @bodyParam price int 售價 Example: 300
   * @bodyParam discount_price int 優惠價 Example: 280
   * @bodyParam start_at datetime 上架時間 Example: 2021-10-10
   * @bodyParam end_at datetime 下架時間 Example: 2021-10-20
   * @bodyParam freight string 運費 Example: 10
   * @bodyParam max_buyable_count string 最大可購買數量 Example: 10
   * @bodyParam storage_space string 商品儲位 Example: AA
   * @bodyParam stock_count string 庫存數量 Example: 100
   * @bodyParam stock_alert_count string 庫存預警數量 Example: 10
   * @bodyParam shop_product int 商品 Example: 1
   * @bodyParam shop_product_spec_settings object 商品規格設定 Example: [1,2,3]
   * @bodyParam shop_product_spec_setting_items object 商品規格設定項目 Example: [1,2,3]
   */
  public function update(Request $request, $id)
  {
    return ModelHelper::ws_UpdateHandler($this, $request, $id);
  }

  /**
   * Delete
   *
   * @urlParam  shop_product_spec required The ID of shop_product_spec. Example: 2
   */
  public function destroy($id)
  {
    return ModelHelper::ws_DestroyHandler($this, $id);
  }

}
