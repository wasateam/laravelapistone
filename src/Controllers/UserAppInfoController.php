<?php

namespace Wasateam\Laravelapistone\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Wasateam\Laravelapistone\Helpers\ModelHelper;

/**
 * @group UserAppInfo
 *
 * @authenticated
 *
 * APIs for user_app_info
 */
class UserAppInfoController extends Controller
{
  public $model        = 'Wasateam\Laravelapistone\Models\UserAppInfo';
  public $name         = 'user_app_info';
  public $resource     = 'Wasateam\Laravelapistone\Resources\UserAppInfo';
  public $input_fields = [
    'scopes',
    'status',
  ];
  public $belongs_to = [
    'user',
    'app',
  ];
  public $filter_belongs_to = [
    'user',
    'app',
  ];
  public $order_fields = [
    'updated_at',
    'created_at',
  ];

  /**
   * Index
   *
   * @queryParam  search string Search on locales.name match No-example
   *
   */
  public function index(Request $request, $id = null)
  {
    return ModelHelper::ws_IndexHandler($this, $request, $id);
  }

  /**
   * Store
   *
   * @bodyParam  scopes string No-example
   * @bodyParam  status string No-example
   * @bodyParam  user int Example: 1
   * @bodyParam  app int Example: 1
   */
  public function store(Request $request, $id = null)
  {
    return ModelHelper::ws_StoreHandler($this, $request, $id);
  }

  /**
   * Show
   *
   * @urlParam  user_app_info required The ID of user_app_info. Example: 1
   */
  public function show(Request $request, $id = null)
  {
    return ModelHelper::ws_ShowHandler($this, $request, $id);
  }

  /**
   * Update
   *
   * @urlParam  user_app_info required The ID of user_app_info. Example: 1
   * @bodyParam  scopes string No-example
   * @bodyParam  status string No-example
   * @bodyParam  user int Example: 1
   * @bodyParam  app int Example: 1
   */
  public function update(Request $request, $id)
  {
    return ModelHelper::ws_UpdateHandler($this, $request, $id);
  }

  /**
   * Delete
   *
   * @urlParam  user_app_info required The ID of user_app_info. Example: 2
   */
  public function destroy($id)
  {
    return ModelHelper::ws_DestroyHandler($this, $id);
  }
}
