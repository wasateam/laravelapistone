<?php

namespace Wasateam\Laravelapistone\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Wasateam\Laravelapistone\Helpers\ModelHelper;
use Wasateam\Laravelapistone\Models\AdminScope;

/**
 * @group AdminScope
 *
 * @authenticated
 *
 * APIs for admin_scope
 */
class AdminScopeController extends Controller
{
  public $model        = 'Wasateam\Laravelapistone\Models\AdminScope';
  public $name         = 'admin_scope';
  public $resource     = 'Wasateam\Laravelapistone\Resources\AdminScope';
  public $input_fields = [
    'name',
    'text',
  ];
  public $search_fields = [
    'name',
    'text',
  ];
  public $order_fields = [
    'updated_at',
    'created_at',
  ];
  public $user_record_field = 'updated_admin_id';
  public $user_create_field = 'created_admin_id';

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
   * @bodyParam name string Example: admin
   * @bodyParam text string Example: Admin
   */
  public function store(Request $request, $id = null)
  {
    return ModelHelper::ws_StoreHandler($this, $request, $id);
  }

  /**
   * Show
   *
   * @urlParam  admin_scope required The ID of admin_scope. Example: 1
   */
  public function show(Request $request, $id = null)
  {
    return ModelHelper::ws_ShowHandler($this, $request, $id);
  }

  /**
   * Update
   *
   * @urlParam  admin_scope required The ID of admin_scope. Example: 1
   * @bodyParam name string Example: admin
   * @bodyParam text string Example: Admin
   */
  public function update(Request $request, $id)
  {
    return ModelHelper::ws_UpdateHandler($this, $request, $id);
  }

  /**
   * Delete
   *
   * @urlParam  admin_scope required The ID of admin_scope. Example: 2
   */
  public function destroy($id)
  {
    return ModelHelper::ws_DestroyHandler($this, $id);
  }
}
