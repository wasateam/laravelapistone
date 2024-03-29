<?php

namespace Wasateam\Laravelapistone\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Wasateam\Laravelapistone\Helpers\ModelHelper;

/**
 * @group ShopProductExpectShip
 *
 * @authenticated
 *
 * APIs for shop_product_expect_ship
 */
class ShopProductExpectShipController extends Controller
{
  public $model        = 'Wasateam\Laravelapistone\Models\ShopProductExpectShip';
  public $name         = 'shop_product_expect_ship';
  public $resource     = 'Wasateam\Laravelapistone\Resources\ShopProductExpectShip';
  public $input_fields = [
    'name',
    'url',
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
   * @bodyParam name string No-example
   * @bodyParam url string No-example
   */
  public function store(Request $request, $id = null)
  {
    return ModelHelper::ws_StoreHandler($this, $request, $id);
  }

  /**
   * Show
   *
   * @urlParam  shop_product_expect_ship required The ID of shop_product_expect_ship. Example: 1
   */
  public function show(Request $request, $id = null)
  {
    return ModelHelper::ws_ShowHandler($this, $request, $id);
  }

  /**
   * Update
   *
   * @urlParam  shop_product_expect_ship required The ID of shop_product_expect_ship. Example: 1
   * @bodyParam name string No-example
   * @bodyParam url string No-example
   */
  public function update(Request $request, $id)
  {
    return ModelHelper::ws_UpdateHandler($this, $request, $id);
  }

  /**
   * Delete
   *
   * @urlParam  shop_product_expect_ship required The ID of shop_product_expect_ship. Example: 2
   */
  public function destroy($id)
  {
    return ModelHelper::ws_DestroyHandler($this, $id);
  }
}
