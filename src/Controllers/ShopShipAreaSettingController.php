<?php

namespace Wasateam\Laravelapistone\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Wasateam\Laravelapistone\Helpers\ModelHelper;

/**
 * @group 配送地區設定
 *
 * @authenticated
 *
 * APIs for shop_ship_area_setting
 */
class ShopShipAreaSettingController extends Controller
{
  public $model        = 'Wasateam\Laravelapistone\Models\ShopShipAreaSetting';
  public $name         = 'shop_ship_area_setting';
  public $resource     = 'Wasateam\Laravelapistone\Resources\ShopShipAreaSetting';
  public $input_fields = [
    'is_all_area_section',
    'ship_ways',
    'type',
  ];
  public $filter_fields = [
    'type',
  ];
  public $belongs_to = [
    'area',
  ];
  public $belongs_to_many = [
    'area_sections',
  ];

  public function __construct()
  {
  }

  /**
   * Index
   * @queryParam search string No-example
   * @queryParam type string No-example
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
   * @urlParam  shop_ship_area_setting required The ID of shop_ship_area_setting. Example: 1
   */
  public function show(Request $request, $id = null)
  {
    return ModelHelper::ws_ShowHandler($this, $request, $id);
  }

  /**
   * Update
   *
   * @urlParam  shop_ship_area_setting required The ID of shop_ship_area_setting. Example: 1
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
   * @urlParam  shop_ship_area_setting required The ID of shop_ship_area_setting. Example: 2
   */
  public function destroy($id)
  {
    return ModelHelper::ws_DestroyHandler($this, $id);
  }
}
