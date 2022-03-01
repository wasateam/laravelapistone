<?php

namespace Wasateam\Laravelapistone\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Wasateam\Laravelapistone\Helpers\ModelHelper;

/**
 * @group Area 地區
 * @authenticated
 *
 * sequence 順序
 * name 名稱
 * country_code 國家代碼
 *
 * APIs for area
 */
class AreaController extends Controller
{
  public $model        = 'Wasateam\Laravelapistone\Models\Area';
  public $name         = 'area';
  public $resource     = 'Wasateam\Laravelapistone\Resources\Area';
  public $input_fields = [
    'sequence',
    'name',
  ];
  public $user_record_field = 'updated_admin_id';
  public $user_create_field = 'created_admin_id';
  public $order_fields      = [
    'sequence',
    'updated_at',
    'created_at',
  ];
  public $search_fields = [
    'name',
  ];
  public $order_by     = 'sequence';
  public $order_way    = 'asc';
  public $country_code = true;

  public function __construct()
  {
    if (config('stone.country_code')) {
      $this->input_fields[]  = 'country_code';
      $this->filter_fields[] = 'country_code';
    }
  }

  /**
   * Index
   *
   * @queryParam  search string Search on locales.name match No-example
   *
   */
  public function index(Request $request, $id = null)
  {
    return ModelHelper::ws_IndexHandler($this, $request, $id, true);
  }

  /**
   * Store
   *
   * @bodyParam  sequence string Example:1
   * @bodyParam  name string Example:aaa
   * @bodyParam country_code string No-example
   */
  public function store(Request $request, $id = null)
  {
    return ModelHelper::ws_StoreHandler($this, $request, $id);
  }

  /**
   * Show
   *
   * @urlParam  area required The ID of area. Example: 1
   */
  public function show(Request $request, $id = null)
  {
    return ModelHelper::ws_ShowHandler($this, $request, $id);
  }

  /**
   * Update
   *
   * @urlParam  area required The ID of area. Example: 1
   * @bodyParam  sequence string Example:1
   * @bodyParam  name string Example:aaa
   * @bodyParam country_code string No-example
   */
  public function update(Request $request, $id)
  {
    return ModelHelper::ws_UpdateHandler($this, $request, $id);
  }

  /**
   * Delete
   *
   * @urlParam  area required The ID of area. Example: 2
   */
  public function destroy($id)
  {
    return ModelHelper::ws_DestroyHandler($this, $id);
  }
}
