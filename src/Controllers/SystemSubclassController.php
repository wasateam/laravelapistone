<?php

namespace Wasateam\Laravelapistone\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Wasateam\Laravelapistone\Helpers\ModelHelper;

/**
 * @group System Subclass
 *
 * @authenticated
 *
 * APIs for system_subclass
 */
class SystemSubclassController extends Controller
{
  public $model        = 'Wasateam\Laravelapistone\Models\SystemSubclass';
  public $name         = 'system_subclass';
  public $resource     = 'Wasateam\Laravelapistone\Resources\SystemSubclass';
  public $input_fields = [
    'sequence',
    'name',
  ];
  public $belongs_to = [
    'system_class',
  ];
  public $filter_belongs_to = [
    'system_class',
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
   * @urlParam system_class int Example:1
   *
   */
  public function index(Request $request, $id = null)
  {
    return ModelHelper::ws_IndexHandler($this, $request, $id, true);
  }

  /**
   * Index Search
   *
   * @queryParam  search string Search on name No-example
   *
   */
  public function index_search(Request $request, $id = null)
  {
    return ModelHelper::ws_IndexHandler($this, $request, $id);
  }

  /**
   * Store
   *
   * @bodyParam  sequence string Example: 1
   * @bodyParam  system_class integer required system_class id. Example: 1
   * @bodyParam  name string Example: aaa
   * @urlParam system_class int Example:1
   */
  public function store(Request $request, $id = null)
  {
    return ModelHelper::ws_StoreHandler($this, $request, $id);
  }

  /**
   * Show
   *
   * @urlParam  system_subclass required The ID of system_subclass. Example: 1
   */
  public function show(Request $request, $id = null)
  {
    return ModelHelper::ws_ShowHandler($this, $request, $id);
  }

  /**
   * Update
   *
   * @urlParam  system_subclass required The ID of system_subclass. Example: 1
   * @bodyParam  sequence string Example: 1
   * @bodyParam  system_class integer required system_class id. Example: 1
   * @bodyParam  name string Example: aaa
   */
  public function update(Request $request, $id)
  {
    return ModelHelper::ws_UpdateHandler($this, $request, $id);
  }

  /**
   * Delete
   *
   * @urlParam  system_subclass required The ID of system_subclass. Example: 2
   */
  public function destroy($id)
  {
    return ModelHelper::ws_DestroyHandler($this, $id);
  }
}
