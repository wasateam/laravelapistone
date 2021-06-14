<?php

namespace Wasateam\Laravelapistone\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Wasateam\Laravelapistone\Helpers\ModelHelper;

/**
 * @group System Class
 *
 * @authenticated
 *
 * APIs for system_class
 */
class SystemClassController extends Controller
{
  public $model        = 'Wasateam\Laravelapistone\Models\SystemClass';
  public $name         = 'system_class';
  public $resource     = 'Wasateam\Laravelapistone\Resources\SystemClass';
  public $table_name   = 'system_classes';
  public $input_fields = [
    'sequence',
    'name',
  ];
  public $belongs_to = [
    'area',
  ];
  public $filter_belongs_to = [
    'area',
  ];
  public $user_record_field = 'updated_admin_id';
  public $user_create_field = 'created_admin_id';
  public $order_fields      = [
    'sequence',
    'updated_at',
    'created_at',
  ];
  public $order_belongs_to = [
    'area',
  ];

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
   * Index With Area
   *
   * @queryParam  search string Search on locales.name match No-example
   * @urlParam area int Example:1
   *
   */
  public function index_with_area(Request $request, $area = null)
  {
    return ModelHelper::ws_IndexHandler($this, $request, null, false, function ($snap) {
      $snap = $snap->where('area_id', $area);
      return $snap;
    });
  }

  /**
   * Store
   *
   * @bodyParam  sequence string Example: 1
   * @bodyParam  area integer required area id. Example: 1
   * @bodyParam  name string Example: aaa
   */
  public function store(Request $request, $id = null)
  {
    return ModelHelper::ws_StoreHandler($this, $request, $id);
  }

  /**
   * Show
   *
   * @urlParam  user required The ID of user. Example: 1
   */
  public function show(Request $request, $id = null)
  {
    return ModelHelper::ws_ShowHandler($this, $request, $id);
  }

  /**
   * Update
   *
   * @urlParam  user required The ID of user. Example: 1
   * @bodyParam  sequence string
   * @bodyParam  area integer area id.
   * @bodyParam  name string
   */
  public function update(Request $request, $id)
  {
    return ModelHelper::ws_UpdateHandler($this, $request, $id);
  }

  /**
   * Delete
   *
   * @urlParam  user required The ID of user. Example: 2
   */
  public function destroy($id)
  {
    return ModelHelper::ws_DestroyHandler($this, $id);
  }
}
