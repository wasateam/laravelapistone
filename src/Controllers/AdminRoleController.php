<?php

namespace Wasateam\Laravelapistone\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Wasateam\Laravelapistone\Helpers\ModelHelper;
use Wasateam\Laravelapistone\Models\AdminRole;

/**
 * @group AdminRole
 *
 * @authenticated
 *
 * APIs for admin_role
 */
class AdminRoleController extends Controller
{
  public $model                   = 'Wasateam\Laravelapistone\Models\AdminRole';
  public $name                    = 'admin_role';
  public $resource                = 'Wasateam\Laravelapistone\Resources\AdminRole';
  public $resource_for_collection = 'Wasateam\Laravelapistone\Resources\AdminRoleCollection';
  public $input_fields            = [
    'name',
    'is_default',
    'scopes',
  ];
  public $search_fields = [
    'name',
  ];
  public $order_fields = [
    'updated_at',
    'created_at',
  ];
  public $user_record_field = 'updated_admin_id';
  public $user_create_field = 'created_admin_id';

  /**
   * Index
   * @urlParam search string No-example
   *
   */
  public function index(Request $request, $id = null)
  {
    return ModelHelper::ws_IndexHandler($this, $request, $id, true);
  }

  /**
   * Store
   *
   * @bodyParam name string Example: admin
   * @bodyParam is_default string Example: false
   * @bodyParam scopes string No-example
   */
  public function store(Request $request, $id = null)
  {
    return ModelHelper::ws_StoreHandler($this, $request, $id);
  }

  /**
   * Show
   *
   * @urlParam  admin_role required The ID of admin_role. Example: 1
   */
  public function show(Request $request, $id = null)
  {
    return ModelHelper::ws_ShowHandler($this, $request, $id);
  }

  /**
   * Update
   *
   * @urlParam  admin_role required The ID of admin_role. Example: 1
   * @bodyParam name string Example: admin
   * @bodyParam is_default string Example: false
   * @bodyParam scopes string No-example
   */
  public function update(Request $request, $id)
  {
    return ModelHelper::ws_UpdateHandler($this, $request, $id);
  }

  /**
   * Delete
   *
   * @urlParam  admin_role required The ID of admin_role. Example: 2
   */
  public function destroy($id)
  {
    return ModelHelper::ws_DestroyHandler($this, $id);
  }
}
