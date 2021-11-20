<?php

namespace Wasateam\Laravelapistone\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Wasateam\Laravelapistone\Helpers\ModelHelper;

/**
 * @group 商品匯入紀錄
 *
 * no 編號
 * uuid
 * storage_space 儲位
 * stock_count 庫存
 * shop_product 所屬商品
 *
 * @authenticated
 */
class ShopProductImportRecordController extends Controller
{
  public $model        = 'Wasateam\Laravelapistone\Models\ShopProductImportRecord';
  public $name         = 'shop_product_import_record';
  public $resource     = 'Wasateam\Laravelapistone\Resources\ShopProductImportRecord';
  public $input_fields = [
    'no',
    'uuid',
    'storage_space',
    'stock_count',
  ];
  public $belongs_to = [
    'shop_product',
  ];
  public $filter_belongs_to = [
    'shop_product',
  ];
  public $search_fields = [
    'no',
    'uuid',
    'storage_space',
    'stock_count',
  ];
  public $order_fields = [
    'updated_at',
    'created_at',
    'stock_count',
  ];
  public $time_fields = [
    'updated_at',
    'created_at',
  ];
  public $uuid = false;

  /**
   * Index
   * @queryParam shop_product ids 所屬商品id  No-example
   * @queryParam order_by string   No-example updated_at,created_at,stock_count
   * @queryParam order_way string  No-example asc,desc
   *
   */
  public function index(Request $request, $id = null)
  {
    return ModelHelper::ws_IndexHandler($this, $request, $id);
  }

  /**
   * Show
   *
   * @urlParam  shop_product_import_record required The ID of shop_product_import_record. Example: 1
   */
  public function show(Request $request, $id = null)
  {
    return ModelHelper::ws_ShowHandler($this, $request, $id);
  }

}
