<?php

namespace Wasateam\Laravelapistone\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Wasateam\Laravelapistone\Helpers\ModelHelper;
use Wasateam\Laravelapistone\Models\AdminGroup;

/**
 * @group AdminGroup
 *
 * @authenticated
 *
 * APIs for admin_group
 */
class AdminGroupController extends Controller
{
  public $model        = 'Wasateam\Laravelapistone\Models\AdminGroup';
  public $name         = 'admin_group';
  public $resource     = 'Wasateam\Laravelapistone\Resources\AdminGroup';
  public $input_fields = [
    'name',
  ];
  public $search_fields = [
    'name',
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
    return ModelHelper::ws_IndexHandler($this, $request, $id, true);
  }

  /**
   * Store
   *
   * @bodyParam name string Example: admin
   */
  public function store(Request $request, $id = null)
  {
    return ModelHelper::ws_StoreHandler($this, $request, $id);
  }

  /**
   * Show
   *
   * @urlParam  admin_group required The ID of admin_group. Example: 1
   */
  public function show(Request $request, $id = null)
  {
    return ModelHelper::ws_ShowHandler($this, $request, $id);
  }

  /**
   * Update
   *
   * @urlParam  admin_group required The ID of admin_group. Example: 1
   * @bodyParam name string Example: admin
   */
  public function update(Request $request, $id)
  {
    return ModelHelper::ws_UpdateHandler($this, $request, $id);
  }

  /**
   * Delete
   *
   * @urlParam  admin_group required The ID of admin_group. Example: 2
   */
  public function destroy($id)
  {
    return ModelHelper::ws_DestroyHandler($this, $id);
  }
}
