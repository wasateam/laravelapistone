<?php

namespace Wasateam\Laravelapistone\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Wasateam\Laravelapistone\Helpers\ModelHelper;
use Wasateam\Laravelapistone\Models\ServiceStoreClose;

/**
 * @group ServiceStoreClose
 *
 * @authenticated
 *
 * APIs for service_store_close
 */
class ServiceStoreCloseController extends Controller
{
  public $model        = 'Wasateam\Laravelapistone\Models\ServiceStoreClose';
  public $name         = 'service_store_close';
  public $resource     = 'Wasateam\Laravelapistone\Resources\ServiceStoreClose';
  public $input_fields = [
    'start',
    'end',
  ];
  public $order_fields = [
    'start',
    'end',
    'updated_at',
    'created_at',
  ];
  public $belongs_to_many = [
    'service_stores',
    'admin_groups',
  ];
  public $scope_filter_belongs_to_many = [
    'admin_groups' => [
      'boss',
    ],
  ];
  public $user_record_field = 'updated_admin_id';
  public $user_create_field = 'created_admin_id';
  public $admin_group       = true;

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
   * @bodyParam start string Example: 2021-08-27 10:00:00
   * @bodyParam end string Example: 2021-08-28 10:00:00
   * @bodyParam service_stores object Example: [1,2,3]
   */
  public function store(Request $request, $id = null)
  {
    return ModelHelper::ws_StoreHandler($this, $request, $id);
  }

  /**
   * Show
   *
   * @urlParam  service_store_close required The ID of service_store_close. Example: 1
   */
  public function show(Request $request, $id = null)
  {
    return ModelHelper::ws_ShowHandler($this, $request, $id);
  }

  /**
   * Update
   *
   * @urlParam  service_store_close required The ID of service_store_close. Example: 1
   * @bodyParam start string Example: 2021-08-27 10:00:00
   * @bodyParam end string Example: 2021-08-28 10:00:00
   * @bodyParam service_stores object Example: [1,2,3]
   */
  public function update(Request $request, $id)
  {
    return ModelHelper::ws_UpdateHandler($this, $request, $id);
  }

  /**
   * Delete
   *
   * @urlParam  service_store_close required The ID of service_store_close. Example: 2
   */
  public function destroy($id)
  {
    return ModelHelper::ws_DestroyHandler($this, $id);
  }
}
