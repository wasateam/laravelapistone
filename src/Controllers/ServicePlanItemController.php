<?php

namespace Wasateam\Laravelapistone\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Wasateam\Laravelapistone\Helpers\ModelHelper;

/**
 * @group ServicePlanItem
 *
 * @authenticated
 *
 * APIs for service_plan_item
 */
class ServicePlanItemController extends Controller
{
  public $model        = 'Wasateam\Laravelapistone\Models\ServicePlanItem';
  public $name         = 'service_plan_item';
  public $resource     = 'Wasateam\Laravelapistone\Resources\ServicePlanItem';
  public $input_fields = [
    'name',
    'type',
    'unit',
    'items',
  ];
  public $search_fields = [
    'name',
  ];
  public $order_fields = [
    'updated_at',
    'created_at',
  ];
  public $uuid = true;

  /**
   * Index
   * @queryParam search string No-example
   *
   */
  public function index(Request $request, $id = null)
  {
    return ModelHelper::ws_IndexHandler($this, $request, $id, true);
  }

  /**
   * Store
   *
   * @bodyParam name string No-example
   * @bodyParam type string No-example
   * @bodyParam unit string No-example
   * @bodyParam items string No-example
   */
  public function store(Request $request, $id = null)
  {
    return ModelHelper::ws_StoreHandler($this, $request, $id);
  }

  /**
   * Show
   *
   * @urlParam  service_plan_item required The ID of service_plan_item. Example: 1
   */
  public function show(Request $request, $id = null)
  {
    return ModelHelper::ws_ShowHandler($this, $request, $id);
  }

  /**
   * Update
   *
   * @urlParam  service_plan_item required The ID of service_plan_item. Example: 1
   * @bodyParam name string No-example
   * @bodyParam type string No-example
   * @bodyParam unit string No-example
   * @bodyParam items string No-example
   */
  public function update(Request $request, $id)
  {
    return ModelHelper::ws_UpdateHandler($this, $request, $id);
  }

  /**
   * Delete
   *
   * @urlParam  service_plan_item required The ID of service_plan_item. Example: 2
   */
  public function destroy($id)
  {
    return ModelHelper::ws_DestroyHandler($this, $id);
  }
}
