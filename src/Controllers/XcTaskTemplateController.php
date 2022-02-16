<?php

namespace Wasateam\Laravelapistone\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Wasateam\Laravelapistone\Helpers\ModelHelper;
use Wasateam\Laravelapistone\Models\XcTaskTemplate;

/**
 * @group XcTaskTemplate 任務 (管理後台)
 *
 * @authenticated
 *
 * name 名稱
 * hour 預估時數
 * is_adjust 是否為調整 Task
 * is_rd 是否為開發 Task
 * is_not_complete 是否尚有項目未完成
 * remark 備註
 * xc_work_type 執行類型 (管理後台)
 *
 */
class XcTaskTemplateController extends Controller
{
  public $model        = 'Wasateam\Laravelapistone\Models\XcTaskTemplate';
  public $name         = 'xc_task_template';
  public $resource     = 'Wasateam\Laravelapistone\Resources\XcTaskTemplate';
  public $input_fields = [
    'name',
    'hour',
    'is_adjust',
    'is_rd',
    'is_not_complete',
    'remark',
  ];
  public $belongs_to = [
    'xc_work_type',
  ];
  public $filter_belongs_to = [
    'xc_work_type',
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
   * hour 預估時數
   * is_adjust 是否為調整
   * is_rd 是否為開發
   * is_not_complete 是否尚有項目未完成
   * remark 備註
   * xc_work_type 執行類型
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
   * hour 預估時數
   * is_adjust 是否為調整
   * is_rd 是否為開發
   * is_not_complete 是否尚有項目未完成
   * remark 備註
   * xc_work_type 執行類型
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
