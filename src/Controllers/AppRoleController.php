<?php

namespace Wasateam\Laravelapistone\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Wasateam\Laravelapistone\Helpers\GcsHelper;
use Wasateam\Laravelapistone\Helpers\ModelHelper;

/**
 * @group AppRole
 *
 * @authenticated
 *
 * APIs for app_role
 */
class AppRoleController extends Controller
{
  public $model        = 'Wasateam\Laravelapistone\Models\AppRole';
  public $name         = 'app_role';
  public $resource     = 'Wasateam\Laravelapistone\Resources\AppRole';
  public $input_fields = [
    'name',
    'scopes',
  ];
  public $belongs_to = [
    'app',
  ];
  public $order_fields = [
    'updated_at',
    'created_at',
  ];

  /**
   * Index
   * @urlParam search string No-example
   *
   */
  public function index(Request $request, $id = null)
  {
    return ModelHelper::ws_IndexHandler($this, $request, $id);
  }

  /**
   * Store
   *
   * @bodyParam name string Example: AAA
   * @bodyParam scopes string No-example
   * @bodyParam app int Example: 1
   */
  public function store(Request $request, $id = null)
  {
    return ModelHelper::ws_IndexHandler($this, $request, $id);
  }

  /**
   * Show
   *
   * @urlParam  app_role required The ID of app_role. Example: 1
   */
  public function show(Request $request, $id = null)
  {
    return ModelHelper::ws_ShowHandler($this, $request, $id);
  }

  /**
   * Update
   *
   * @urlParam  app_role required The ID of app_role. Example: 1
   * @bodyParam name string Example: AAA
   * @bodyParam scopes string No-example
   * @bodyParam app int Example: 1
   */
  public function update(Request $request, $id)
  {
    return ModelHelper::ws_UpdateHandler($this, $request, $id);
  }

  /**
   * Delete
   *
   * @urlParam  app_role required The ID of app_role. Example: 2
   */
  public function destroy($id)
  {
    return ModelHelper::ws_DestroyHandler($this, $id);
  }
}
