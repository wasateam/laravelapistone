<?php

namespace Wasateam\Laravelapistone\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Wasateam\Laravelapistone\Helpers\ModelHelper;
use Wasateam\Laravelapistone\Models\XcTask;

/**
 * @group XcTask 任務 (管理後台)
 *
 * @authenticated
 *
 * name 名稱
 * start_at 開始時間
 * reviewed_at 覆核時間
 * status 狀態
 * ~ open 待執行
 * ~ done 已完成
 * ~ closed 已覆核
 * hour 預估時數
 * finish_hour 完成回報時數
 * creator_remark 建立人備註
 * taker_remark 執行人備註
 * is_adjust 是否為調整 Task
 * is_rd 是否為開發 Task
 * is_not_complete 是否尚有項目未完成
 * xc_work_type 執行類型 (管理後台)
 * xc_project 專案 (管理後台)
 * creator 建立人
 * taker 執行人
 *
 */
class XcTaskController extends Controller
{
  public $model        = 'Wasateam\Laravelapistone\Models\XcTask';
  public $name         = 'xc_task';
  public $resource     = 'Wasateam\Laravelapistone\Resources\XcTask';
  public $input_fields = [
    'name',
    'start_at',
    'reviewed_at',
    'status',
    'open',
    'done',
    'closed',
    'hour',
    'finish_hour',
    'creator_remark',
    'taker_remark',
    'is_adjust',
    'is_rd',
    'is_not_complete',
  ];
  public $belongs_to = [
    'xc_work_type',
    'xc_project',
    'creator',
    'taker',
  ];
  public $filter_belongs_to = [
    'xc_work_type',
    'xc_project',
    'creator',
    'taker',
  ];
  public $order_fields = [
    'updated_at',
    'created_at',
  ];

  public function __construct()
  {
  }

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
   * name 名稱
   * start_at 開始時間
   * reviewed_at 覆核時間
   * status 狀態
   * hour 預估時數
   * finish_hour 完成回報時數
   * creator_remark 建立人備註
   * taker_remark 執行人備註
   * is_adjust 是否為調整
   * is_rd 是否為開發
   * is_not_complete 是否尚有項目未完成
   * xc_work_type 執行類型
   * xc_project 專案
   * creator 建立人
   * taker 執行人
   *
   */
  public function store(Request $request, $id = null)
  {
    return ModelHelper::ws_StoreHandler($this, $request, $id);
  }

  /**
   * Show
   *
   * @urlParam  task required The ID of task. Example: 1
   */
  public function show(Request $request, $id = null)
  {
    return ModelHelper::ws_ShowHandler($this, $request, $id);
  }

  /**
   * Update
   *
   * @urlParam  task required The ID of task. Example: 1
   * name 名稱
   * start_at 開始時間
   * reviewed_at 覆核時間
   * status 狀態
   * hour 預估時數
   * finish_hour 完成回報時數
   * creator_remark 建立人備註
   * taker_remark 執行人備註
   * is_adjust 是否為調整
   * is_rd 是否為開發
   * is_not_complete 是否尚有項目未完成
   * xc_work_type 執行類型
   * xc_project 專案
   * creator 建立人
   * taker 執行人
   */
  public function update(Request $request, $id)
  {
    return ModelHelper::ws_UpdateHandler($this, $request, $id);
  }

  /**
   * Delete
   *
   * @urlParam  task required The ID of task. Example: 2
   */
  public function destroy($id)
  {
    return ModelHelper::ws_DestroyHandler($this, $id);
  }
}
