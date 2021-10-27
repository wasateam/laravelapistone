<?php

namespace Wasateam\Laravelapistone\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Wasateam\Laravelapistone\Helpers\ModelHelper;
use Wasateam\Laravelapistone\Models\UserServicePlan;

/**
 * @group UserServicePlan
 *
 * @authenticated
 *
 * APIs for user_service_plan
 */
class UserServicePlanController extends Controller
{
  public $model        = 'Wasateam\Laravelapistone\Models\UserServicePlan';
  public $name         = 'user_service_plan';
  public $resource     = 'Wasateam\Laravelapistone\Resources\UserServicePlan';
  public $input_fields = [
  ];
  public $search_fields = [
  ];
  public $belongs_to = [
    'service_plan',
  ];
  public $filter_belongs_to = [
    'service_plan',
  ];
  public $order_fields = [
    'updated_at',
    'created_at',
  ];

  public function __construct()
  {
    if (config('stone.mode') == 'cms') {
      $this->belongs_to = [
        'user',
        'service_plan',
      ];
      $this->filter_belongs_to = [
        'user',
        'service_plan',
      ];
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
   * @bodyParam user string Example: 1
   * @bodyParam service_plan string Example: 1
   */
  public function store(Request $request, $id = null)
  {
    return ModelHelper::ws_StoreHandler($this, $request, $id);
  }

  /**
   * Show
   *
   * @urlParam  user_service_plan required The ID of user_service_plan. Example: 1
   */
  public function show(Request $request, $id = null)
  {
    return ModelHelper::ws_ShowHandler($this, $request, $id);
  }

  /**
   * Update
   *
   * @urlParam  user_service_plan required The ID of user_service_plan. Example: 1
   * @bodyParam user string Example: 1
   * @bodyParam service_plan string Example: 1
   */
  public function update(Request $request, $id)
  {
    return ModelHelper::ws_UpdateHandler($this, $request, $id);
  }

  /**
   * Delete
   *
   * @urlParam  user_service_plan required The ID of user_service_plan. Example: 2
   */
  public function destroy($id)
  {
    return ModelHelper::ws_DestroyHandler($this, $id);
  }
}
