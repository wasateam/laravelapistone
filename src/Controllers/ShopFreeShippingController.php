<?php

namespace Wasateam\Laravelapistone\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Wasateam\Laravelapistone\Helpers\ModelHelper;

/**
 * @group 免運門檻
 *
 * 免運門檻列表API
 * name 免運名稱
 * price 免運金額
 * start_date 開始日期
 * end_date 結束日期
 *
 * @authenticated
 */
class ShopFreeShippingController extends Controller
{
  public $model                   = 'Wasateam\Laravelapistone\Models\ShopFreeShipping';
  public $name                    = 'shop_free_shipping';
  public $resource                = 'Wasateam\Laravelapistone\Resources\ShopFreeShipping';
  public $resource_for_collection = 'Wasateam\Laravelapistone\Resources\ShopFreeShippingCollection';
  public $input_fields            = [
    'name',
    'start_date',
    'end_date',
    'price',
  ];
  public $search_fields = [
    'name',
  ];

  /**
   * Index
   * @queryParam search string 搜尋字串 No-example
   *
   */
  public function index(Request $request, $id = null)
  {
    return ModelHelper::ws_IndexHandler($this, $request, $id);
  }

  /**
   * Store
   *
   * @bodyParam name string 免運名稱 Example:name
   * @bodyParam price int 免運金額 Example:1000
   * @bodyParam start_date string 開始日期 Example:2021-10-10
   * @bodyParam end_date string 結束日期 Example:2021-10-20
   */
  public function store(Request $request, $id = null)
  {
    return ModelHelper::ws_StoreHandler($this, $request, $id);
  }

  /**
   * Show
   *
   * @urlParam  shop_free_shipping required The ID of shop_free_shipping. Example: 1
   */
  public function show(Request $request, $id = null)
  {
    return ModelHelper::ws_ShowHandler($this, $request, $id);
  }

  /**
   * Update
   *
   * @bodyParam name string 免運名稱 Example:name
   * @bodyParam price int 免運金額 Example:1000
   * @bodyParam start_date string 開始日期 Example:2021-10-10
   * @bodyParam end_date string 結束日期 Example:2021-10-20
   */
  public function update(Request $request, $id)
  {
    return ModelHelper::ws_UpdateHandler($this, $request, $id);
  }

  /**
   * Delete
   *
   * @urlParam  shop_free_shipping required The ID of shop_free_shipping. Example: 2
   */
  public function destroy($id)
  {
    return ModelHelper::ws_DestroyHandler($this, $id);
  }
}
