<?php

namespace Wasateam\Laravelapistone\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Wasateam\Laravelapistone\Helpers\ModelHelper;
use Wasateam\Laravelapistone\Resources\UserServicePlan;

/**
 * @group UserServicePlan 使用者綁定方案
 *
 * service_plan 方案 ID
 * user 使用者 ID
 * pin_card PinCard ID
 *
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
  public $search_relationship_fields = [
    'user' => [
      'name',
      'email',
      'tel',
    ],
  ];

  public function __construct()
  {
    if (config('stone.mode') == 'cms') {
      $this->belongs_to[]        = 'user';
      $this->filter_belongs_to[] = 'user';
    }
    if (config('stone.pin_card')) {
      $this->belongs_to[]        = 'pin_card';
      $this->filter_belongs_to[] = 'pin_card';
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
   * @bodyParam pin_card string Example: 1
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
   * @bodyParam pin_card string Example: 1
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

  /**
   * My Current Plan
   *
   */
  public function my_current_plan()
  {
    $user              = Auth::user();
    $user_service_plan = $this->model::where('user_id', $user->id)
      ->where('expired_at', '>=', Carbon::now())
      ->orderBy('created_at', 'desc')
      ->first();

    return response()->json(new UserServicePlan($user_service_plan), 200);
  }
}
