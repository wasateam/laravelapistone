<?php

namespace Wasateam\Laravelapistone\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Wasateam\Laravelapistone\Helpers\ModelHelper;

/**
 * @group  CmsLog
 *
 * APIs for web_log
 *
 * @authenticated
 */
class WebLogController extends Controller
{
  public $model        = 'Wasateam\Laravelapistone\Models\WebLog';
  public $name         = 'web_log';
  public $resource     = 'Wasateam\Laravelapistone\Resources\WebLog';
  public $input_fields = [
    "payload",
    "type",
  ];
  public $belongs_to = [
    'user',
  ];
  public $filter_belongs_to = [
    'user',
  ];
  public $order_fields = [
    'updated_at',
    'created_at',
  ];
  public $search_fields = [
    'payload',
  ];

  /**
   * Index
   *
   * @queryParam admin int No-Example 1
   * @queryParam search string No-Example name
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
   * @urlParam  web_log required The ID of web_log. Example: 1
   */
  public function show(Request $request, $id = null)
  {
    return ModelHelper::ws_ShowHandler($this, $request, $id);
  }

  /**
   * Update
   *
   * @urlParam web_log Example: 1
   * @bodyParam name string Example: Subject 1
   */
  public function update(Request $request, $id)
  {
    return ModelHelper::ws_UpdateHandler($this, $request, $id);
  }

  /**
   * Delete
   *
   * @urlParam web_log Example: 2
   */
  public function destroy($id)
  {
    return ModelHelper::ws_DestroyHandler($this, $id);
  }
}
