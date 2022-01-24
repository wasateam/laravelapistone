<?php

namespace Wasateam\Laravelapistone\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Wasateam\Laravelapistone\Helpers\ModelHelper;

/**
 * @group ShopProductSpecSetting 產品規格設定
 *
 * 產品規格設定 API
 *
 * name 產品規格設定名稱
 * sq 排序
 * shop_product 綁定之商品
 *
 * @authenticated
 */
class ShopProductSpecSettingController extends Controller
{
  public $model        = 'Wasateam\Laravelapistone\Models\ShopProductSpecSetting';
  public $name         = 'shop_product_spec_setting';
  public $resource     = 'Wasateam\Laravelapistone\Resources\ShopProductSpecSetting';
  public $input_fields = [
    'sq',
    'name',
  ];
  public $filter_fields = [
  ];
  public $order_fields = [
    'sq',
  ];
  public $belongs_to = [
    'shop_product',
  ];
  public $filter_belongs_to = [
    'shop_product',
  ];

  public function __construct()
  {
  }

  /**
   * Index
   * @queryParam shop_product ids 商品  No-example
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
   * @urlParam  shop_product_spec_setting required The ID of shop_product_spec_setting. Example: 1
   */
  public function show(Request $request, $id = null)
  {
    return ModelHelper::ws_ShowHandler($this, $request, $id);
  }

  /**
   * Update
   *
   * @urlParam  shop_product_spec_setting required The ID of shop_product_spec_setting. Example: 1
   * @bodyParam shop_product int 產品 Example:1
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
   * @urlParam  shop_product_spec_setting required The ID of shop_product_spec_setting. Example: 2
   */
  public function destroy($id)
  {
    return ModelHelper::ws_DestroyHandler($this, $id);
  }

}
