<?php

namespace Wasateam\Laravelapistone\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Wasateam\Laravelapistone\Helpers\ModelHelper;

/**
 * @group ShopProductImage
 *
 * @authenticated
 *
 * APIs for shop_product_image
 */
class ShopProductImageController extends Controller
{
  public $model        = 'Wasateam\Laravelapistone\Models\ShopProductImage';
  public $name         = 'shop_product_image';
  public $resource     = 'Wasateam\Laravelapistone\Resources\ShopProductImage';
  public $input_fields = [
    'url',
    'name',
    'sq',
  ];
  public $belongs_to = [
    'shop_product',
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
   * @bodyParam url string No-example
   * @bodyParam name string No-example
   * @bodyParam sq string No-example
   * @bodyParam shop_product id No-example
   */
  public function store(Request $request, $id = null)
  {
    return ModelHelper::ws_StoreHandler($this, $request, $id);
  }

  /**
   * Show
   *
   * @urlParam  shop_product_image required The ID of shop_product_image. Example: 1
   */
  public function show(Request $request, $id = null)
  {
    return ModelHelper::ws_ShowHandler($this, $request, $id);
  }

  /**
   * Update
   *
   * @urlParam  shop_product_image required The ID of shop_product_image. Example: 1
   * @bodyParam url string No-example
   * @bodyParam name string No-example
   * @bodyParam sq string No-example
   * @bodyParam shop_product id No-example
   */
  public function update(Request $request, $id)
  {
    return ModelHelper::ws_UpdateHandler($this, $request, $id);
  }

  /**
   * Delete
   *
   * @urlParam  shop_product_image required The ID of shop_product_image. Example: 2
   */
  public function destroy($id)
  {
    return ModelHelper::ws_DestroyHandler($this, $id);
  }
}
