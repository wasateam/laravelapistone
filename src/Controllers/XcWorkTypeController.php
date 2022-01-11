<?php

namespace Wasateam\Laravelapistone\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Wasateam\Laravelapistone\Helpers\ModelHelper;
use Wasateam\Laravelapistone\Models\XcWorkType;

/**
 * @group XcWorkType 工種 (管理後台)
 *
 * @authenticated
 *
 * name 明稱
 * sq 排序設定
 */
class XcWorkTypeController extends Controller
{
  public $model        = 'Wasateam\Laravelapistone\Models\XcWorkType';
  public $name         = 'xc_work_type';
  public $resource     = 'Wasateam\Laravelapistone\Resources\XcWorkType';
  public $input_fields = [
    'name',
    'sq',
  ];
  public $order_fields = [
    'updated_at',
    'created_at',
  ];
  public $country_code = true;

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
   * bodyParam name string Example: Cool
   * bodyParam sq  string Example: 123
   * 
   */
  public function store(Request $request, $id = null)
  {
    return ModelHelper::ws_StoreHandler($this, $request, $id);
  }

  /**
   * Show
   *
   * @urlParam  xc_work_type required The ID of xc_work_type. Example: 1
   */
  public function show(Request $request, $id = null)
  {
    return ModelHelper::ws_ShowHandler($this, $request, $id);
  }

  /**
   * Update
   *
   * @urlParam  xc_work_type required The ID of xc_work_type. Example: 1
   * bodyParam name string Example: Cool
   * bodyParam sq  string Example: 123
   */
  public function update(Request $request, $id)
  {
    return ModelHelper::ws_UpdateHandler($this, $request, $id);
  }

  /**
   * Delete
   *
   * @urlParam  xc_work_type required The ID of xc_work_type. Example: 2
   */
  public function destroy($id)
  {
    return ModelHelper::ws_DestroyHandler($this, $id);
  }
}
