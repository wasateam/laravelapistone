<?php

namespace Wasateam\Laravelapistone\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Wasateam\Laravelapistone\Helpers\ModelHelper;
use Wasateam\Laravelapistone\Models\ServicePlan;

/**
 * @group ServicePlan
 *
 * @authenticated
 *
 * APIs for service_plan
 */
class ServicePlanController extends Controller
{
  public $model        = 'Wasateam\Laravelapistone\Models\ServicePlan';
  public $name         = 'service_plan';
  public $resource     = 'Wasateam\Laravelapistone\Resources\ServicePlan';
  public $input_fields = [
    'name',
    'remark',
    'payload',
  ];
  public $belongs_to_many = [
    'service_plan',
  ];
  public $search_fields = [
    'name',
  ];
  public $order_fields = [
    'updated_at',
    'created_at',
  ];
  public $user_create_field = 'created_admin_id';
  public $uuid              = true;

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
   * @bodyParam name string Example: Plan A
   * @bodyParam remark string Example: hahahaha
   * @bodyParam payload string No-example
   */
  public function store(Request $request, $id = null)
  {
    return ModelHelper::ws_StoreHandler($this, $request, $id);
  }

  /**
   * Show
   *
   * @urlParam  service_plan required The ID of service_plan. Example: 1
   */
  public function show(Request $request, $id = null)
  {
    return ModelHelper::ws_ShowHandler($this, $request, $id);
  }

  /**
   * Update
   *
   * @urlParam  service_plan required The ID of service_plan. Example: 1
   * @bodyParam name string Example: Plan A
   * @bodyParam remark string Example: hahahaha
   * @bodyParam payload string No-example
   */
  public function update(Request $request, $id)
  {
    return ModelHelper::ws_UpdateHandler($this, $request, $id);
  }

  /**
   * Delete
   *
   * @urlParam  service_plan required The ID of service_plan. Example: 2
   */
  public function destroy($id)
  {
    return ModelHelper::ws_DestroyHandler($this, $id);
  }
}
