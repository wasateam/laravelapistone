<?php

namespace Wasateam\Laravelapistone\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Wasateam\Laravelapistone\Helpers\ModelHelper;

/**
 * @group ShopProductSpecSettingItem 產品規格設定項目
 *
 * 產品規格設定項目 API
 *
 * name 產品規格設定項目名稱
 * sq 排序
 * shop_product 綁定之商品
 * shop_product_spec_setting 綁定之商品設定
 *
 * @authenticated
 */
class ShopProductSpecSettingItemController extends Controller
{
  public $model         = 'Wasateam\Laravelapistone\Models\ShopProductSpecSettingItem';
  public $name          = 'shop_product_spec_setting_item';
  public $resource      = 'Wasateam\Laravelapistone\Resources\ShopProductSpecSettingItem';
  public $input_fields  = [];
  public $filter_fields = [
    'sq',
    'name',
  ];
  public $belongs_to = [
    'shop_product',
    'shop_product_spec_setting',
  ];
  public $filter_belongs_to = [
    'shop_product',
    'shop_product_spec_setting',
  ];

  public function __construct()
  {
  }

  /**
   * Index
   * @queryParam shop_product ids 商品  No-example
   * @queryParam shop_product_spec_setting ids 商品  No-example
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
   * @bodyParam shop_product int 產品 Example:1
   * @bodyParam shop_product_spec_setting int 產品 Example:1
   * @bodyParam name string 名稱 Example:name
   * @bodyParam sq string 名稱 Example:1
   */
  public function store(Request $request, $id = null)
  {
    return ModelHelper::ws_StoreHandler($this, $request, $id);
  }

  /**
   * Show
   *
   * @urlParam  shop_product_spec_setting_item required The ID of shop_product_spec_setting_item. Example: 1
   */
  public function show(Request $request, $id = null)
  {
    return ModelHelper::ws_ShowHandler($this, $request, $id);
  }

  /**
   * Update
   *
   * @urlParam  shop_product_spec_setting_item required The ID of shop_product_spec_setting_item. Example: 1
   * @bodyParam shop_product int 產品 Example:1
   * @bodyParam shop_product_spec_setting int 產品 Example:1
   * @bodyParam name string 名稱 Example:name
   * @bodyParam sq string 名稱 Example:1
   */
  public function update(Request $request, $id)
  {
    return ModelHelper::ws_UpdateHandler($this, $request, $id);
  }

  /**
   * Delete
   *
   * @urlParam  shop_product_spec_setting_item required The ID of shop_product_spec_setting_item. Example: 2
   */
  public function destroy($id)
  {
    return ModelHelper::ws_DestroyHandler($this, $id);
  }

}
