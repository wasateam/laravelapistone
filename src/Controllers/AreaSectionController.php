<?php

namespace Wasateam\Laravelapistone\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Wasateam\Laravelapistone\Helpers\ModelHelper;

/**
 * @group AreaSection
 *
 * @authenticated
 *
 * APIs for area_section
 */
class AreaSectionController extends Controller
{
  public $model        = 'Wasateam\Laravelapistone\Models\AreaSection';
  public $name         = 'area_section';
  public $resource     = 'Wasateam\Laravelapistone\Resources\AreaSection';
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
  public $order_fields = [
    'sequence',
    'updated_at',
    'created_at',
  ];

  /**
   * Index
   *
   * @queryParam  search string Search on locales.name match No-example
   * @urlParam area int Example:1
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
   * @urlParam area int Example:1
   * @bodyParam  name string Example:aaa
   */
  public function store(Request $request, $id = null)
  {
    return ModelHelper::ws_StoreHandler($this, $request, $id);
  }

  /**
   * Show
   *
   * @urlParam  area_section required The ID of area_section. Example: 1
   */
  public function show(Request $request, $id = null)
  {
    return ModelHelper::ws_ShowHandler($this, $request, $id);
  }

  /**
   * Update
   *
   * @urlParam  area_section required The ID of area_section. Example: 1
   * @bodyParam  sequence string Example:1
   * @bodyParam  name string Example:aaa
   */
  public function update(Request $request, $id)
  {
    return ModelHelper::ws_UpdateHandler($this, $request, $id);
  }

  /**
   * Delete
   *
   * @urlParam  area_section required The ID of area_section. Example: 2
   */
  public function destroy($id)
  {
    return ModelHelper::ws_DestroyHandler($this, $id);
  }
}
