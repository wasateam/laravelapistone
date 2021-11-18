<?php

namespace Wasateam\Laravelapistone\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Wasateam\Laravelapistone\Helpers\ModelHelper;

/**
 * @group ShopSubclass
 *
 * @authenticated
 *
 * APIs for shop_subclass
 */
class ShopSubclassController extends Controller
{
  public $model        = 'Wasateam\Laravelapistone\Models\ShopSubclass';
  public $name         = 'shop_subclass';
  public $resource     = 'Wasateam\Laravelapistone\Resources\ShopSubclass';
  public $input_fields = [
    'name',
    'sq',
    'type',
  ];
  public $belongs_to = [
    'shop_class',
  ];
  public $filter_belongs_to = [
    'shop_class',
  ];
  public $order_fields = [
    'sq',
  ];
  public $order_by = 'sq';
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
   * @bodyParam sq string No-example
   * @bodyParam type string No-example
   * @bodyParam shop_class id No-example
   */
  public function store(Request $request, $id = null)
  {
    return ModelHelper::ws_StoreHandler($this, $request, $id);
  }

  /**
   * Show
   *
   * @urlParam  shop_subclass required The ID of shop_subclass. Example: 1
   */
  public function show(Request $request, $id = null)
  {
    return ModelHelper::ws_ShowHandler($this, $request, $id);
  }

  /**
   * Update
   *
   * @urlParam  shop_subclass required The ID of shop_subclass. Example: 1
   * @bodyParam name string No-example
   * @bodyParam sq string No-example
   * @bodyParam type string No-example
   * @bodyParam shop_class id No-example
   */
  public function update(Request $request, $id)
  {
    return ModelHelper::ws_UpdateHandler($this, $request, $id);
  }

  /**
   * Delete
   *
   * @urlParam  shop_subclass required The ID of shop_subclass. Example: 2
   */
  public function destroy($id)
  {
    return ModelHelper::ws_DestroyHandler($this, $id);
  }
}
