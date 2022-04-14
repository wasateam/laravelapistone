<?php

namespace Wasateam\Laravelapistone\Controllers;

use App\Http\Controllers\Controller;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Wasateam\Laravelapistone\Helpers\ModelHelper;
use Wasateam\Laravelapistone\Helpers\RequestHelper;
use Wasateam\Laravelapistone\Helpers\ServiceStoreHelper;
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
    'user' => [
      'name',
    ],
  ];
  public $belongs_to = [
    'user',
    'service_store',
  ];
  public $filter_belongs_to = [
    'user',
    'service_store',
  ];
  public $order_fields = [
    'updated_at',
    'created_at',
    'date',
  ];
  public $filter_time_fields = [
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
      RequestHelper::requiredFieldsCheck($request,
        [
          'service_store',
          'start_time',
          'end_time',
        ]
      );
      ServiceStoreHelper::appointableCheck($request);
      return  ;
      return ModelHelper::ws_StoreHandler($this, $request, $id, function ($model) use ($request) {
        $model->start_time = ServiceStoreHelper::getServiceStoreTime($request->start_time, $request->service_store);
        $model->end_time   = ServiceStoreHelper::getServiceStoreTime($request->end_time, $request->service_store);
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

  /**
   * Export Excel Signedurl
   *
   */
  public function export_excel_signedurl(Request $request)
  {
    return ModelHelper::ws_ExportExcelSignedurlHandler($this, $request);

  }

  /**
   * Export Excel
   *
   */
  public function export_excel(Request $request)
  {
    return ModelHelper::ws_ExportExcelHandler(
      $this,
      $request,
      [
        '預約日期',
        '預約時段(起訖時間)',
        '服務中心名稱',
        '會員編號',
        '會員名稱',
        '電話',
        'Email',
        '備註',
      ],
      function ($model) {
        return [
          Carbon::parse($model->created_at)->format('Y-m-d'),
          $model->start_time . " ~ " . $model->end_time,
          $model->service_store ? $model->service_store->name : null,
          $model->user ? $model->user->customer_id : null,
          $model->user ? $model->user->name : null,
          $model->user ? $model->user->email : null,
          $model->user ? $model->user->tel : null,
          $model->remark,
        ];
      }
    );
  }
}
