<?php

namespace Wasateam\Laravelapistone\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Wasateam\Laravelapistone\Helpers\ModelHelper;
use Wasateam\Laravelapistone\Models\ServicePlan;

/**
 * @group ServicePlan 服務方案
 *
 *
 * name 名稱
 * code 代碼(識別用)
 * remark 備註
 * payload
 * period_month 時間(月)
 * total_price  總價
 * annual_price  年費
 * monthly_price  月費
 */
class ServicePlanController extends Controller
{
  public $model        = 'Wasateam\Laravelapistone\Models\ServicePlan';
  public $name         = 'service_plan';
  public $resource     = 'Wasateam\Laravelapistone\Resources\ServicePlan';
  public $input_fields = [
    'name',
    'code',
    'remark',
    'payload',
    'period_month',
    'total_price',
    'annual_price',
    'monthly_price',
    'changed_times_limit',
    'limit'
  ];
  public $search_fields = [
    'name',
    'code',
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
    return ModelHelper::ws_IndexHandler($this, $request, $id);
  }

  /**
   * Store
   *
   * @bodyParam name string Example: Plan A
   * @bodyParam code string Example: AAA
   * @bodyParam remark string Example: hahahaha
   * @bodyParam payload string No-example
   * @bodyParam period_month int No-example
   * @bodyParam total_price int No-example
   * @bodyParam annual_price int No-example
   * @bodyParam monthly_price int No-example
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
   * @bodyParam code string Example: AAA
   * @bodyParam remark string Example: hahahaha
   * @bodyParam payload string No-example
   * @bodyParam period_month int No-example
   * @bodyParam total_price int No-example
   * @bodyParam annual_price int No-example
   * @bodyParam monthly_price int No-example
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
