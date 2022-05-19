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
 * content 內容
 * start_date 日期
 * days 執行天數
 * done_at 完成時間
 * remark 備註
 * xc_project 專案 (管理後台)
 * creator 建立人
 *
 */
class XcMilestoneController extends Controller
{
  public $model        = 'Wasateam\Laravelapistone\Models\XcMilestone';
  public $name         = 'xc_milestone';
  public $resource     = 'Wasateam\Laravelapistone\Resources\XcMilestone';
  public $input_fields = [
    'name',
    'content',
    'start_date',
    'days',
    'remark',
  ];
  public $belongs_to = [
    'xc_project',
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
   * content 內容
   * start_date 日期
   * days 執行天數
   * remark 備註
   * xc_project 專案 (管理後台)
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
   * content 內容
   * start_date 日期
   * days 執行天數
   * remark 備註
   * xc_project 專案 (管理後台)
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
