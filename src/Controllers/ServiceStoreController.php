<?php

namespace Wasateam\Laravelapistone\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Wasateam\Laravelapistone\Helpers\ModelHelper;
use Wasateam\Laravelapistone\Models\ServiceStore;

/**
 * @group ServiceStore
 *
 * @authenticated
 *
 * APIs for service_store
 */
class ServiceStoreController extends Controller
{
  public $model        = 'Wasateam\Laravelapistone\Models\ServiceStore';
  public $name         = 'service_store';
  public $resource     = 'Wasateam\Laravelapistone\Resources\ServiceStore';
  public $input_fields = [
    'name',
    'type',
    'cover_image',
    'tel',
    'address',
    'des',
    'business_hours',
    'appointment_availables',
    'lat',
    'lng',
    'is_active',
    'payload',
    'parking_info',
    'transportation_info',
  ];
  public $belongs_to_many = [
  ];
  public $search_fields = [
    'name',
  ];
  public $order_fields = [
    'updated_at',
    'created_at',
  ];
  public $scope_filter_belongs_to_many = [
    'admin_groups' => [
      'boss',
    ],
  ];
  public $user_record_field = 'updated_admin_id';
  public $user_create_field = 'created_admin_id';
  public $uuid              = true;
  public $admin_group       = true;

  public function __construct()
  {
    if (config('stone.admin_group')) {
      $this->belongs_to_many = [
        'admin_groups',
      ];
    }
  }

  /**
   * Index
   * @urlParam search string No-example
   *
   */
  public function index(Request $request, $id = null)
  {
    if (config('stone.mode') == 'cms') {
      return ModelHelper::ws_IndexHandler($this, $request, $id);
    } else if (config('stone.mode') == 'webapi') {
      return ModelHelper::ws_IndexHandler($this, $request, $id, false, function ($snap) {
        $snap = $snap->where('is_active', 1);
        return $snap;
      });
    }
  }

  /**
   * Store
   *
   * @bodyParam name string Example: Store A
   * @bodyParam type string Example: wow
   * @bodyParam cover_image string No-example
   * @bodyParam tel string Example: 0999-999-999
   * @bodyParam address string Example: 台北市中山區松江路333號33樓
   * @bodyParam des string Example: 說明說明
   * @bodyParam business_hours object Example: ["0900","2000","0900","2000","0900","2000","0900","2000","0900","2000","1000","1800",null,null]
   * @bodyParam appointment_availables object No-example
   * @bodyParam lat string Example: 121.564558
   * @bodyParam lng string Example: 25.03746
   * @bodyParam is_active int Example: 1
   * @bodyParam payload object No-example
   * @bodyParam parking_info string No-example
   * @bodyParam transportation_info string No-example
   */
  public function store(Request $request, $id = null)
  {
    return ModelHelper::ws_StoreHandler($this, $request, $id);
  }

  /**
   * Show
   *
   * @urlParam  service_store required The ID of service_store. Example: 1
   */
  public function show(Request $request, $id = null)
  {
    if (config('stone.mode') == 'cms') {
      return ModelHelper::ws_ShowHandler($this, $request, $id);
    } else if (config('stone.mode') == 'webapi') {
      return ModelHelper::ws_ShowHandler($this, $request, $id, function ($snap) {
        $snap = $snap->where('is_active', 1);
        return $snap;
      });
    };
  }

  /**
   * Update
   *
   * @urlParam  service_store required The ID of service_store. Example: 1
   * @bodyParam name string Example: Store A
   * @bodyParam type string Example: wow
   * @bodyParam cover_image string No-example
   * @bodyParam tel string Example: 0999-999-999
   * @bodyParam address string Example: 台北市中山區松江路333號33樓
   * @bodyParam des string Example: 說明說明
   * @bodyParam business_hours object Example: ["0900","2000","0900","2000","0900","2000","0900","2000","0900","2000","1000","1800",null,null]
   * @bodyParam appointment_availables object No-example
   * @bodyParam lat string Example: 121.564558
   * @bodyParam lng string Example: 25.03746
   * @bodyParam is_active int Example: 1
   * @bodyParam payload object No-example
   * @bodyParam parking_info string No-example
   * @bodyParam transportation_info string No-example
   */
  public function update(Request $request, $id)
  {
    return ModelHelper::ws_UpdateHandler($this, $request, $id);
  }

  /**
   * Delete
   *
   * @urlParam  service_store required The ID of service_store. Example: 2
   */
  public function destroy($id)
  {
    return ModelHelper::ws_DestroyHandler($this, $id);
  }
}
