<?php

namespace Wasateam\Laravelapistone\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Wasateam\Laravelapistone\Helpers\ModelHelper;

/**
 * @group ShopClass
 *
 * @authenticated
 *
 * APIs for shop_class
 */
class ShopClassController extends Controller
{
  public $model        = 'Wasateam\Laravelapistone\Models\ShopClass';
  public $name         = 'shop_class';
  public $resource     = 'Wasateam\Laravelapistone\Resources\ShopClass';
  public $input_fields = [
    'name',
  ];
  public $belongs_to = [
    'parent_shop_class',
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
   * @urlParam search string No-example
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
   * @bodyParam shop_product id No-example
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
   * @bodyParam name string No-example
   * @bodyParam shop_product id No-example
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
}
