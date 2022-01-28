<?php

namespace Wasateam\Laravelapistone\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Wasateam\Laravelapistone\Helpers\ModelHelper;

/**
 * @group PageSetting 頁面設定
 *
 * APIs for page_setting
 *
 * name 名稱
 * route 頁面路徑 前面須加斜線 ex. /route
 *
 * @authenticated
 */
class PageSettingController extends Controller
{
  public $model        = 'Wasateam\Laravelapistone\Models\PageSetting';
  public $name         = 'page_setting';
  public $resource     = 'Wasateam\Laravelapistone\Resources\PageSetting';
  public $input_fields = [
    'name',
    'route',
  ];
  public $uuid = false;

  /**
   * Index
   */
  public function index(Request $request, $id = null)
  {
    return ModelHelper::ws_IndexHandler($this, $request, $id);
  }

  /**
   * Store
   *
   * @bodyParam name string Example:index
   * @bodyParam route id Example:/index
   */
  public function store(Request $request, $id = null)
  {
    return ModelHelper::ws_StoreHandler($this, $request, $id);
  }

  /**
   * Show
   *
   * @urlParam  page_setting required The ID of page_setting. Example: 1
   */
  public function show(Request $request, $id = null)
  {
    return ModelHelper::ws_ShowHandler($this, $request, $id);
  }

  /**
   * Update
   *
   * @urlParam  page_setting required The ID of page_setting. Example: 1
   * @bodyParam name string Example:index
   * @bodyParam route id Example:/index
   */
  public function update(Request $request, $id)
  {
    return ModelHelper::ws_UpdateHandler($this, $request, $id);
  }

  /**
   * Delete
   *
   * @urlParam  page_setting required The ID of page_setting. Example: 1
   */
  public function destroy($id)
  {
    return ModelHelper::ws_DestroyHandler($this, $id);
  }
}
