<?php

namespace Wasateam\Laravelapistone\Controllers;

use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Http\Request;
use Wasateam\Laravelapistone\Helpers\ModelHelper;

/**
 * @group TulpaCrossItem
 *
 * @authenticated
 *
 * APIs for tulpa_cross_item
 */
class TulpaCrossItemController extends Controller
{
  public $model        = 'Wasateam\Laravelapistone\Models\TulpaCrossItem';
  public $name         = 'tulpa_cross_item';
  public $resource     = 'Wasateam\Laravelapistone\Resources\TulpaCrossItem';
  public $input_fields = [
    'name',
    'position',
    'content',
  ];
  public $search_fields = [
    'name',
  ];
  public $belongs_to = [
    'tulpa_section',
  ];
  public $belongs_to_many = [
    'admin_groups',
  ];
  public $order_fields = [
    'updated_at',
    'created_at',
  ];
  public $scope_filter_belongs_to_many = [
    'admin_groups' => [
      'boss',
    ],
  ];
  public $admin_group = true;

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
   * @bodyParam name string Example: My Page 1
   * @bodyParam position string Example: bottom
   * @bodyParam content object No-example
   * @bodyParam tulpa_section int Example: 1
   * @bodyParam admin_groups object No-example
   */
  public function store(Request $request, $id = null)
  {
    return ModelHelper::ws_StoreHandler($this, $request, $id);
  }

  /**
   * Show
   *
   * @urlParam  tulpa_cross_item required The ID of tulpa_cross_item. Example: 1
   */
  public function show(Request $request, $id = null)
  {
    return ModelHelper::ws_ShowHandler($this, $request, $id);
  }

  /**
   * Update
   *
   * @urlParam  tulpa_cross_item required The ID of tulpa_cross_item. Example: 1
   * @bodyParam name string Example: My Page 1
   * @bodyParam position string Example: bottom
   * @bodyParam content object No-example
   * @bodyParam tulpa_section int Example: 1
   * @bodyParam admin_groups object No-example

   */
  public function update(Request $request, $id)
  {
    return ModelHelper::ws_UpdateHandler($this, $request, $id);
  }

  /**
   * Delete
   *
   * @urlParam  tulpa_cross_item required The ID of tulpa_cross_item. Example: 2
   */
  public function destroy($id)
  {
    return ModelHelper::ws_DestroyHandler($this, $id);
  }
}
