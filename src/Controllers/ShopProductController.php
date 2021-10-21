<?php

namespace Wasateam\Laravelapistone\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Wasateam\Laravelapistone\Helpers\ModelHelper;

/**
 * @group ShopProduct
 *
 * @authenticated
 *
 * APIs for shop_product
 */
class ShopProductController extends Controller
{
  public $model                   = 'Wasateam\Laravelapistone\Models\ShopProduct';
  public $name                    = 'shop_product';
  public $resource                = 'Wasateam\Laravelapistone\Resources\ShopProduct';
  public $resource_for_collection = 'Wasateam\Laravelapistone\Resources\ShopProductCollection';
  public $input_fields            = [
    'type',
    'no',
    'name',
    'subtitle',
    'status',
    'on_time',
    'off_time',
    'is_active',
    'spec',
    'cost',
    'price',
    'discount_price',
    'weight_capacity',
    'tax',
    'stock_count',
    'stock_alert_count',
    'max_buyable_count',
    'storage_space',
    'cover_image',
    'description',
    'source',
    'store_temperature',
    'ranking_score',
  ];
  public $search_fields = [
    'no',
    'uuid',
    'name',
    'storage_space',
  ];
  public $belongs_to = [
    'shop_product_cover_frame',
  ];
  public $filter_fields = [
    'tax',
    'is_active',
    'status',
    'type',
    'source',
    'store_temperature',
  ];
  public $belongs_to_many = [
    'suggests',
  ];
  public $order_fields = [
    'ranking_score',
    'updated_at',
    'created_at',
    'price',
    'discount_price',
    'stock_count',
  ];
  public $uuid = false;

  public function __construct()
  {
    if (config('stone.shop.uuid')) {
      $this->uuid = true;
    }
    if (config('stone.featured_class')) {
      $this->belongs_to_many[] = 'featured_classes';
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
   * @bodyParam no string No-example
   * @bodyParam name string No-example
   * @bodyParam subtitle string No-example
   * @bodyParam status string No-example
   * @bodyParam on_time string No-example
   * @bodyParam off_time string No-example
   * @bodyParam is_active string No-example
   * @bodyParam spec string No-example
   * @bodyParam cost string No-example
   * @bodyParam price string No-example
   * @bodyParam discount_price string No-example
   * @bodyParam weight_capacity string No-example
   * @bodyParam tax string No-example
   * @bodyParam stock_count string No-example
   * @bodyParam stock_alert_count string No-example
   * @bodyParam max_buyable_count string No-example
   * @bodyParam storage_space string No-example
   * @bodyParam cover_image string No-example
   * @bodyParam description string No-example
   * @bodyParam source string No-example
   * @bodyParam store_temperature string No-example
   * @bodyParam ranking_score string No-example
   * @bodyParam shop_product_cover_frame id No-example
   * @bodyParam suggests ids No-example
   */
  public function store(Request $request, $id = null)
  {
    return ModelHelper::ws_StoreHandler($this, $request, $id);
  }

  /**
   * Show
   *
   * @urlParam  shop_product required The ID of shop_product. Example: 1
   */
  public function show(Request $request, $id = null)
  {
    return ModelHelper::ws_ShowHandler($this, $request, $id);
  }

  /**
   * Update
   *
   * @urlParam  shop_product required The ID of shop_product. Example: 1
   * @bodyParam no string No-example
   * @bodyParam name string No-example
   * @bodyParam subtitle string No-example
   * @bodyParam status string No-example
   * @bodyParam on_time string No-example
   * @bodyParam off_time string No-example
   * @bodyParam is_active string No-example
   * @bodyParam spec string No-example
   * @bodyParam cost string No-example
   * @bodyParam price string No-example
   * @bodyParam discount_price string No-example
   * @bodyParam weight_capacity string No-example
   * @bodyParam tax string No-example
   * @bodyParam stock_count string No-example
   * @bodyParam stock_alert_count string No-example
   * @bodyParam max_buyable_count string No-example
   * @bodyParam storage_space string No-example
   * @bodyParam cover_image string No-example
   * @bodyParam description string No-example
   * @bodyParam source string No-example
   * @bodyParam store_temperature string No-example
   * @bodyParam ranking_score string No-example
   * @bodyParam shop_product_cover_frame id No-example
   * @bodyParam suggests ids No-example
   */
  public function update(Request $request, $id)
  {
    return ModelHelper::ws_UpdateHandler($this, $request, $id);
  }

  /**
   * Delete
   *
   * @urlParam  shop_product required The ID of shop_product. Example: 2
   */
  public function destroy($id)
  {
    return ModelHelper::ws_DestroyHandler($this, $id);
  }
}
