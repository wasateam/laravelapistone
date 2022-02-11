<?php

namespace Wasateam\Laravelapistone\Controllers;

use App\Http\Controllers\Controller;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Wasateam\Laravelapistone\Helpers\ModelHelper;
use Wasateam\Laravelapistone\Helpers\TimeHelper;
use Wasateam\Laravelapistone\Models\Appointment;

/**
 * @group Appointment
 * @authenticated
 *
 * start_time 起始時間 (單純時間四位)
 * end_time 結束時間 (單純時間四位)
 * date 日期
 * tel 電話
 * email 信箱
 * type 類型
 * remark 備註
 * user 使用者
 * service_store 服務據點
 * country_code 國家代碼
 */
class AppointmentController extends Controller
{
  public $model        = 'Wasateam\Laravelapistone\Models\Appointment';
  public $name         = 'appointment';
  public $resource     = 'Wasateam\Laravelapistone\Resources\Appointment';
  public $input_fields = [
    'start_time',
    'end_time',
    'date',
    'tel',
    'email',
    'type',
    'remark',
  ];
  public $search_relationship_fields = [
    'user' =>[
      'name',
    ],
  ];
  public $belongs_to = [
    'user',
    'service_store',
  ];
  public $order_fields = [
    'updated_at',
    'created_at',
    'date',
  ];

  public function __construct()
  {
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
      return ModelHelper::ws_IndexHandler($this, $request, $id, false, function ($snap) {
        $user = Auth::user();
        $snap = $snap->where('user_id', $user->id);
        return $snap;
      });
    }
  }

  /**
   * Store
   *
   * @bodyParam start_time string Example: 1000
   * @bodyParam end_time string Example: 1200
   * @bodyParam date date Example: 2021-03-13
   * @bodyParam user string Example :1
   * @bodyParam service_store Example :1
   * @bodyParam tel String Example: 02-2222-2222
   * @bodyParam email String Example: aa@aa.aa
   * @bodyParam type String No-example
   * @bodyParam remark String No-example
   */
  public function store(Request $request, $id = null)
  {
    if (config('stone.mode') == 'cms') {
      return ModelHelper::ws_StoreHandler($this, $request, $id);
    } else if (config('stone.mode') == 'webapi') {
      return ModelHelper::ws_StoreHandler($this, $request, $id, function ($model) {
        $user           = Auth::user();
        $model->user_id = $user->id;
        $datetime       = Carbon::parse($model->date);
        if (config('stone.country_code')) {
          $timezone = TimeHelper::getTimeZone($model->country_code);
          $datetime = $datetime->timezone($timezone);
        }
        $start_at = TimeHelper::setTimeFromHrMinStr($datetime, $model->start_time);
        $end_at   = TimeHelper::setTimeFromHrMinStr($datetime, $model->end_time);
        if (config('stone.country_code')) {
          $start_at->timezone(config('app.timezone'));
          $end_at->timezone(config('app.timezone'));
        }
        $model->start_at = $start_at;
        $model->end_at   = $end_at;
        $model->save();
      });
    }
  }

  /**
   * Show
   *
   * @urlParam  appointment required The ID of appointment. Example: 1
   */
  public function show(Request $request, $id = null)
  {

    if (config('stone.mode') == 'cms') {
      return ModelHelper::ws_ShowHandler($this, $request, $id);
    } else if (config('stone.mode') == 'webapi') {
      return ModelHelper::ws_ShowHandler($this, $request, $id, function ($snap) {
        $user = Auth::user();
        $snap = $snap->where('user_id', $user->id);
        return $snap;
      });
    }
  }

  /**
   * Update
   *
   * @urlParam  appointment required The ID of appointment. Example: 1
   * @bodyParam start_time string Example: 1000
   * @bodyParam end_time string Example: 1200
   * @bodyParam date date Example: 2021-03-13
   * @bodyParam user string Example :1
   * @bodyParam service_store Example :1
   * @bodyParam tel String Example: 02-2222-2222
   * @bodyParam email String Example: aa@aa.aa
   * @bodyParam type String No-example
   * @bodyParam remark String No-example
   */
  public function update(Request $request, $id)
  {
    if (config('stone.mode') == 'cms') {
      return ModelHelper::ws_UpdateHandler($this, $request, $id);
    } else if (config('stone.mode') == 'webapi') {
      return ModelHelper::ws_UpdateHandler($this, $request, $id, [], null, function ($model) {
        $user = Auth::user();
        if ($model->user_id == $user->id) {
          return true;
        } else {
          return false;
        }
      });
    }
  }

  /**
   * Delete
   *
   * @urlParam  appointment required The ID of appointment. Example: 2
   */
  public function destroy($id)
  {
    if (config('stone.mode') == 'cms') {
      return ModelHelper::ws_DestroyHandler($this, $id);
    } else if (config('stone.mode') == 'webapi') {
      return ModelHelper::ws_DestroyHandler($this, $id, null, null, function ($model) {
        $user = Auth::user();
        if ($model->user_id == $user->id) {
          return true;
        } else {
          return false;
        }
      });
    }
  }
}
