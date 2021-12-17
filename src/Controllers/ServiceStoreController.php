<?php

namespace Wasateam\Laravelapistone\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Wasateam\Laravelapistone\Helpers\ModelHelper;
use Wasateam\Laravelapistone\Models\ServiceStore;

/**
 * @group 服務店家 ServiceStore
 *
 * name 名稱
 * type 類型
 * cover_image 封面圖片
 * tel 聯絡電話
 * address 地址
 * des 描述
 * business_hours 營業時間
 * - 格式:["0900","2000","0900","2000","0900","2000","0900","2000","0900","2000","1000","1800",null,null]ｚ
 * lat 經度
 * lng 緯度
 * is_active 啟用狀態
 * payload
 * parking_infos 停車場資訊
 *  - link 地點連結 (Map)
 *  - info 資訊文字
 * parking_image 停車場示意圖片
 * transportation_info 交通資訊
 * work_on_holiday 是否週末營業
 * service_at_night 是否夜間服務
 * country_code 國家代碼
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
    'lat',
    'lng',
    'is_active',
    'payload',
    'parking_info',
    'parking_infos',
    'parking_image',
    'parking_link',
    'transportation_info',
    'work_on_holiday',
    'service_at_night',
  ];
  public $belongs_to      = [];
  public $belongs_to_many = [
  ];
  public $search_fields = [
    'name',
    'address',
  ];
  public $filter_fields = [
    'work_on_holiday',
    'service_at_night',
  ];
  public $order_fields = [
    'updated_at',
    'created_at',
  ];
  public $scope_filter_belongs_to_many = [];
  public $user_record_field            = 'updated_admin_id';
  public $user_create_field            = 'created_admin_id';
  public $uuid                         = true;
  public $country_code                 = true;
  // public $admin_group                  = true;

  public function __construct()
  {

    if (config('stone.admin_group')) {
      if (config('stone.admin_blur')) {
        $this->belongs_to_many = [
          'cmser_groups',
        ];
        $this->scope_filter_belongs_to_many = [
          'cmser_groups' => [
            'boss',
          ],
        ];
      } else {
        $this->belongs_to_many = [
          'admin_groups',
        ];
        $this->scope_filter_belongs_to_many = [
          'admin_groups' => [
            'boss',
          ],
        ];
      }
    }

    if (config('stone.appointment')) {
      $this->input_fields[] = 'appointment_availables';
    }

    if (config('stone.area')) {
      $this->belongs_to[]        = 'area';
      $this->filter_belongs_to[] = 'area';
    }

    if (config('stone.country_code')) {
      $this->input_fields[]  = 'country_code';
      $this->filter_fields[] = 'country_code';
    }
  }

  /**
   * Index
   * @queryParam search string No-example
   *
   */
  public function index(Request $request, $id = null)
  {
    if (config('stone.mode') == 'cms') {
      return ModelHelper::ws_IndexHandler($this, $request, $id);
    } else if (config('stone.mode') == 'webapi') {
      return ModelHelper::ws_IndexHandler($this, $request, $id, true, function ($snap) use ($request) {
        $snap = $snap->where('is_active', 1);

        // Ready to deprecate
        if ($request->country) {
          $snap = $snap->whereHas('admin_groups', function ($query) use ($request) {
            $query = $query->where('name', '=', $request->country);
          });
        }

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
   * @bodyParam parking_infos array No-example
   * @bodyParam parking_link string No-example
   * @bodyParam parking_image string No-example
   * @bodyParam transportation_info string No-example
   * @bodyParam work_on_holiday boolean No-example
   * @bodyParam service_at_night boolean No-example
   * @bodyParam country_code string No-example
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
   * @bodyParam parking_infos array No-example
   * @bodyParam parking_link string No-example
   * @bodyParam parking_image string No-example
   * @bodyParam transportation_info string No-example
   * @bodyParam work_on_holiday boolean No-example
   * @bodyParam service_at_night boolean No-example
   * @bodyParam country_code string No-example
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
