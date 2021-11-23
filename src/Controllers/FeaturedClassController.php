<?php

namespace Wasateam\Laravelapistone\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Wasateam\Laravelapistone\Helpers\ModelHelper;

/**
 * @group FeaturedClass
 *
 * @authenticated
 *
 * APIs for featured_class
 */
class FeaturedClassController extends Controller
{
  public $model        = 'Wasateam\Laravelapistone\Models\FeaturedClass';
  public $name         = 'featured_class';
  public $resource     = 'Wasateam\Laravelapistone\Resources\FeaturedClass';
  public $input_fields = [
    'name',
    'icon',
    'sequence',
  ];
  public $order_fields = [
    'sequence',
  ];
  public $uuid = false;

  public function __construct()
  {
    if (config('stone.shop.uuid')) {
      $this->uuid = true;
    }
    if (config('stone.mode') == 'cms') {
      $this->belongs_to_many[] = "shop_products";
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
   * @bodyParam name string Example:name
   * @bodyParam shop_product id Example:1
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
   * @bodyParam name string Example:name
   * @bodyParam shop_product id Example:1
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
