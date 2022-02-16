<?php

namespace Wasateam\Laravelapistone\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Wasateam\Laravelapistone\Helpers\ModelHelper;
use Wasateam\Laravelapistone\Models\UserServicePlanRecord;
use Auth;

/**
 * @group UserServicePlanRecord 使用者方案綁定紀錄
 *
 * @authenticated
 *
 * count 數量
 * remark 備註
 * user 使用者
 * service_plan 方案
 * service_plan_item 方案項目
 * user_service_plan 會員綁定方案
 * user_service_plan_item 會員綁定方案項目
 * 
 */
class UserServicePlanRecordController extends Controller
{
  public $model        = 'Wasateam\Laravelapistone\Models\UserServicePlanRecord';
  public $name         = 'user_service_plan_record';
  public $resource     = 'Wasateam\Laravelapistone\Resources\UserServicePlanRecord';
  public $input_fields = [
    'count',
    'remark',
  ];
  public $search_fields = [
    'remark',
  ];
  public $belongs_to = [
    'user',
    'service_plan',
    'service_plan_item',
    'user_service_plan',
    'user_service_plan_item',
  ];
  public $filter_belongs_to = [
    'user',
    'service_plan',
    'service_plan_item',
    'user_service_plan',
    'user_service_plan_item',
  ];
  public $order_fields = [
    'updated_at',
    'created_at',
  ];

  public function __construct()
  {
    if (config('stone.mode') == 'cms') {
      $this->belongs_to[]        = 'admin';
      $this->filter_belongs_to[] = 'admin';
    }
  }

  /**
   * Index
   * @queryParam search string No-example
   *
   */
  public function index(Request $request, $id = null)
  {
    if(config('stone.user.service_history')){
      return config('stone.user.service_history')::getUserOrderHistory(Auth::user());
    }
    return ModelHelper::ws_IndexHandler($this, $request, $id);
  }

  /**
   * Store
   *
   * @bodyParam count int Example: 1
   * @bodyParam remark string Example: asdfasdfasdfas
   * @bodyParam user id Example: 1
   * @bodyParam service_plan id Example: 1
   * @bodyParam service_plan_item id Example: 1
   * @bodyParam user_service_plan id Example: 1
   * @bodyParam user_service_plan_item id Example: 1
   * @bodyParam admin id Example: 1
   */
  public function store(Request $request, $id = null)
  {
    return ModelHelper::ws_StoreHandler($this, $request, $id);
  }

  /**
   * Show
   *
   * @urlParam  user_service_plan_record required The ID of user_service_plan_record. Example: 1
   */
  public function show(Request $request, $id = null)
  {
    return ModelHelper::ws_ShowHandler($this, $request, $id);
  }

  /**
   * Update
   *
   * @urlParam  user_service_plan_record required The ID of user_service_plan_record. Example: 1
   * @bodyParam count int Example: 1
   * @bodyParam remark string Example: asdfasdfasdfas
   * @bodyParam user id Example: 1
   * @bodyParam service_plan id Example: 1
   * @bodyParam service_plan_item id Example: 1
   * @bodyParam user_service_plan id Example: 1
   * @bodyParam user_service_plan_item id Example: 1
   * @bodyParam admin id Example: 1
   */
  public function update(Request $request, $id)
  {
    return ModelHelper::ws_UpdateHandler($this, $request, $id);
  }

  /**
   * Delete
   *
   * @urlParam  user_service_plan_record required The ID of user_service_plan_record. Example: 2
   */
  public function destroy($id)
  {
    return ModelHelper::ws_DestroyHandler($this, $id);
  }
}
