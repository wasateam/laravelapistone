<?php

namespace Wasateam\Laravelapistone\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Wasateam\Laravelapistone\Helpers\ModelHelper;
use Wasateam\Laravelapistone\Models\ServiceStoreNoti;

/**
 * @group ServiceStoreNoti
 *
 * @authenticated
 *
 * APIs for service_store_noti
 */
class ServiceStoreNotiController extends Controller
{
  public $model        = 'Wasateam\Laravelapistone\Models\ServiceStoreNoti';
  public $name         = 'service_store_noti';
  public $resource     = 'Wasateam\Laravelapistone\Resources\ServiceStoreNoti';
  public $input_fields = [
    'content',
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
   * @bodyParam content String Example: aaaaaaaaa
   * @bodyParam service_stores object Example: [1,2,3]
   */
  public function store(Request $request, $id = null)
  {
    return ModelHelper::ws_StoreHandler($this, $request, $id);
  }

  /**
   * Show
   *
   * @urlParam  service_store_noti required The ID of service_store_noti. Example: 1
   */
  public function show(Request $request, $id = null)
  {
    return ModelHelper::ws_ShowHandler($this, $request, $id);
  }

  /**
   * Update
   *
   * @urlParam  service_store_noti required The ID of service_store_noti. Example: 1
   * @bodyParam content String Example: aaaaaaaaa
   * @bodyParam service_stores object Example: [1,2,3]
   */
  public function update(Request $request, $id)
  {
    return ModelHelper::ws_UpdateHandler($this, $request, $id);
  }

  /**
   * Delete
   *
   * @urlParam  service_store_noti required The ID of service_store_noti. Example: 2
   */
  public function destroy($id)
  {
    return ModelHelper::ws_DestroyHandler($this, $id);
  }
}
