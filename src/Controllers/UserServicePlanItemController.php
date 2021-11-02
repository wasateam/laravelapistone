<?php

namespace Wasateam\Laravelapistone\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Wasateam\Laravelapistone\Helpers\ModelHelper;
use Wasateam\Laravelapistone\Models\UserServicePlanItem;

/**
 * @group UserServicePlanItem
 *
 * @authenticated
 *
 * APIs for user_service_plan_item
 */
class UserServicePlanItemController extends Controller
{
  public $model        = 'Wasateam\Laravelapistone\Models\UserServicePlanItem';
  public $name         = 'user_service_plan_item';
  public $resource     = 'Wasateam\Laravelapistone\Resources\UserServicePlanItem';
  public $input_fields = [
    'content',
    'expired_at',
    'total_count',
    'remain_count',
  ];
  public $search_fields = [
  ];
  public $belongs_to = [
    'service_plan',
    'service_plan_item',
    'user_service_plan',
  ];
  public $filter_belongs_to = [
    'service_plan',
    'service_plan_item',
    'user_service_plan',
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
        'service_plan_item',
      ];
      $this->filter_belongs_to = [
        'user',
        'service_plan_item',
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
   * @bodyParam service_plan_item string Example: 1
   * @bodyParam content string Example: 1
   * @bodyParam expired_at date Example: 2021-10-10 10:00:00
   * @bodyParam total_count int No-example
   * @bodyParam remain_count int No-example
   */
  public function store(Request $request, $id = null)
  {
    return ModelHelper::ws_StoreHandler($this, $request, $id);
  }

  /**
   * Show
   *
   * @urlParam  user_service_plan_item required The ID of user_service_plan_item. Example: 1
   */
  public function show(Request $request, $id = null)
  {
    return ModelHelper::ws_ShowHandler($this, $request, $id);
  }

  /**
   * Update
   *
   * @urlParam  user_service_plan_item required The ID of user_service_plan_item. Example: 1
   * @bodyParam user string Example: 1
   * @bodyParam service_plan_item string Example: 1
   * @bodyParam content string Example: 1
   * @bodyParam expired_at date Example: 2021-10-10 10:00:00
   * @bodyParam total_count int No-example
   * @bodyParam remain_count int No-example
   */
  public function update(Request $request, $id)
  {
    return ModelHelper::ws_UpdateHandler($this, $request, $id);
  }

  /**
   * Delete
   *
   * @urlParam  user_service_plan_item required The ID of user_service_plan_item. Example: 2
   */
  public function destroy($id)
  {
    return ModelHelper::ws_DestroyHandler($this, $id);
  }

  /**
   * Remain Count Deduct
   *
   * @urlParam  user_service_plan_item required The ID of user_service_plan_item. Example: 2
   */
  public function remain_count_deduct($id)
  {
    try {
      $model = $this->model::find($id);
    } catch (\Throwable $th) {
      return response()->json([
        'message' => 'no data.',
      ], 400);
    }

    if (!$model) {
      return response()->json([
        'message' => 'no data.',
      ], 400);
    }

    if ($model->remain_count > 0) {
      $model->remain_count--;
      $model->save();
    }

    return response()->json([
      "message" => 'deducted.',
    ], 200);
  }
}
