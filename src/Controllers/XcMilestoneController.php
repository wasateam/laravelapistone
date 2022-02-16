<?php

namespace Wasateam\Laravelapistone\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Wasateam\Laravelapistone\Helpers\ModelHelper;
use Wasateam\Laravelapistone\Models\XcMilestone;

/**
 * @group XcMilestone Milestone (管理後台)
 *
 * @authenticated
 *
 * name 名稱
 * date 日期
 * reviewed_at 覆核時間
 * status 狀態
 * ~ open 待執行
 * ~ done 已完成
 * ~ closed 已覆核
 * creator_remark 建立人備註
 * taker_remark 執行人備註
 * xc_project 專案 (管理後台)
 * creator 建立人
 * taker 執行人
 * is_personal 個人 task
 *
 */
class XcMilestoneController extends Controller
{
  public $model        = 'Wasateam\Laravelapistone\Models\XcMilestone';
  public $name         = 'xc_milestone';
  public $resource     = 'Wasateam\Laravelapistone\Resources\XcMilestone';
  public $input_fields = [
    'name',
    'date',
    'reviewed_at',
    'status',
    'creator_remark',
    'taker_remark',
    'is_personal',
  ];
  public $belongs_to = [
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
    return ModelHelper::ws_IndexHandler($this, $request, $id);
  }

  /**
   * Store
   *
   * name 名稱
   * datte 日期
   * reviewed_at 覆核時間
   * status 狀態
   * creator_remark 建立人備註
   * taker_remark 執行人備註
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
   * datte 日期
   * reviewed_at 覆核時間
   * status 狀態
   * creator_remark 建立人備註
   * taker_remark 執行人備註
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
