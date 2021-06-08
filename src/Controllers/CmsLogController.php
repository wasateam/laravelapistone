<?php

namespace Wasateam\Laravelapistone\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Wasateam\Laravelapistone\Helpers\ModelHelper;

/**
 * @group  CmsLog
 *
 * APIs for cms_log
 *
 * @authenticated
 */
class CmsLogController extends Controller
{
  public $model        = 'Wasateam\Laravelapistone\Models\CmsLog';
  public $name         = 'cms_log';
  public $resource     = 'Wasateam\Laravelapistone\Resources\CmsLog';
  public $input_fields = [
    "payload",
    "type",
  ];

  /**
   * Index
   *
   */
  public function index(Request $request, $id = null)
  {
    return ModelHelper::ws_IndexHandler($this, $request, $id);
  }

  /**
   * Store
   *
   * @bodyParam type string Example: Subject 1
   * @bodyParam payload object Example: []
   */
  public function store(Request $request, $id = null)
  {
    return ModelHelper::ws_StoreHandler($this, $request, $id);
  }

  /**
   * Show
   *
   * @urlParam  cms_log required The ID of cms_log. Example: 1
   */
  public function show(Request $request, $id = null)
  {
    return ModelHelper::ws_ShowHandler($this, $request, $id);
  }

  /**
   * Update
   *
   * @urlParam cms_log Example: 1
   * @bodyParam name string Example: Subject 1
   */
  public function update(Request $request, $id)
  {
    return ModelHelper::ws_UpdateHandler($this, $request, $id);
  }

  /**
   * Delete
   *
   * @urlParam cms_log Example: 2
   */
  public function destroy($id)
  {
    return ModelHelper::ws_DestroyHandler($this, $id);
  }
}
