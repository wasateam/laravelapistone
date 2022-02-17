<?php

namespace Wasateam\Laravelapistone\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\URL;
use Maatwebsite\Excel\Facades\Excel;
use Wasateam\Laravelapistone\Exports\UserExport;
use Wasateam\Laravelapistone\Helpers\AuthHelper;
use Wasateam\Laravelapistone\Helpers\ModelHelper;

/**
 * @group User
 *
 * APIs for user
 *
 * name 名稱
 * email Email
 * email_verified_at email認證時間
 * password 密碼
 * status 狀態
 * ~ 0 停用
 * ~ 1 啟用
 * avatar 頭像
 * settings
 * description
 * scopes 權限
 * tel 電話
 * payload
 * is_active 是否啟用
 * sequence 排序
 * updated_admin_at
 * verified_at 認證時間
 * byebye_at 刪除(實際上資料還在)
 * mama_language 常用語言
 * is_bad 黑名單 bad bad user
 * bonus_points 紅利點數
 * birthday 生日
 * gender 性別
 * ~ female: 女森
 * ~ male: 男森
 * carrier_email 信箱載具
 * carrier_phone 電話載具
 * carrier_certificate 自然人憑證載具
 * subscribe_start_at 訂閱開始時間
 * subscribe_end_at 訂閱結束時間
 * color 顏色
 * customer_id 系統自動生成之客戶ID
 * acumatica_id Acumatica ID
 *
 * @authenticated
 */
class UserController extends Controller
{
  public $model               = 'Wasateam\Laravelapistone\Models\User';
  public $name                = 'user';
  public $resource            = 'Wasateam\Laravelapistone\Resources\User';
  public $validation_messages = [
    'password.min' => 'password too short.',
    'email.unique' => 'email has been token.',
  ];
  public $validation_rules = [
    'email'         => "required|string|email|unique:users",
    'name'          => 'required|string|min:1|max:40',
    'carrier_email' => 'email|string',
  ];
  public $input_fields = [
    'name',
    'email',
    'email_verified_at',
    'password',
    'status',
    'avatar',
    'settings',
    'description',
    'scopes',
    'tel',
    'payload',
    'is_active',
    'sequence',
    'updated_admin_at',
    'verified_at',
    "byebye_at",
    "mama_language",
    "is_bad",
    "bonus_points",
    "birthday",
    "gender",
    "subscribe_start_at",
    "subscribe_end_at",
    "color",
  ];
  public $filter_fields = [
    'byebye_at',
    'is_active',
    'is_bad',
  ];
  public $search_fields = [
    'id',
    'name',
    'email',
    'uuid',
    'tel',
    'customer_id'
  ];
  public $order_fields = [
    'id',
    'updated_at',
    'created_at',
  ];
  public $belongs_to = [
  ];
  public $user_record_field = 'updated_admin_id';
  public $user_create_field = 'created_admin_id';
  public $uuid              = false;

  public function __construct()
  {
    if (config('stone.user.uuid')) {
      $this->uuid = true;
    }
    if (config('stone.locale')) {
      $this->belongs_to = [
        'locale',
      ];
    }
    if (config('stone.user.is_bad')) {
      $this->input_fields[] = 'is_bad';
    }
    if (config('stone.user.bonus_points')) {
      $this->input_fields[] = 'bonus_points';
    }
    if (config('stone.user.carriers')) {
      $this->input_fields[] = 'carrier_email';
      $this->input_fields[] = 'carrier_phone';
      $this->input_fields[] = 'carrier_certificate';
    }
    if (config('stone.acumatica')) {
      $this->input_fields[] = 'acumatica_id';
    }
  }

  /**
   * Index
   * @queryParam search string No-example
   *
   */
  public function index(Request $request, $id = null)
  {
    return ModelHelper::ws_IndexHandler($this, $request, $id);
  }

  /**
   * Store
   *
   * @bodyParam name string No-example
   * @bodyParam email string No-example
   * @bodyParam email_verified_at datetime No-example
   * @bodyParam password string No-example
   * @bodyParam status integer No-example
   * @bodyParam avatar string No-example
   * @bodyParam settings string No-example
   * @bodyParam description string No-example
   * @bodyParam scopes object No-example
   * @bodyParam tel string No-example
   * @bodyParam payload object No-example
   * @bodyParam is_bad boolean No-example
   * @bodyParam bonus_points int No-example
   * @bodyParam birthday date No-example
   * @bodyParam gender string Example:male,female
   * @bodyParam is_active boolean No-example
   * @bodyParam carrier_email string Example:aa@aa.com
   * @bodyParam carrier_phone string Example:0900000000
   * @bodyParam carrier_certificate string Example:123456789
   * @bodyParam subscribe_start_at datetime No-example
   * @bodyParam subscribe_end_at datetime No-example
   * @bodyParam color string #000000
   * @bodyParam acumatica_id string No-example
   */
  public function store(Request $request, $id = null)
  {
    $model = $this->model;
    return ModelHelper::ws_StoreHandler($this, $request, $id, function ($user) use ($model) {
      if (config('stone.auth.customer_id')) {
        $user->customer_id = AuthHelper::getCustomerId($model, config('stone.auth.customer_id'));
        $user->save();
      }
    });
  }

  /**
   * Show
   *
   * @urlParam  user required The ID of user. Example: 1
   */
  public function show(Request $request, $id = null)
  {
    return ModelHelper::ws_ShowHandler($this, $request, $id);
  }

  /**
   * Update
   *
   * @urlParam  user required The ID of user. Example: 1
   * @bodyParam name string No-example
   * @bodyParam email string No-example
   * @bodyParam email_verified_at datetime No-example
   * @bodyParam password string No-example
   * @bodyParam status integer No-example
   * @bodyParam avatar string No-example
   * @bodyParam settings string No-example
   * @bodyParam description string No-example
   * @bodyParam scopes object No-example
   * @bodyParam tel string No-example
   * @bodyParam payload object No-example
   * @bodyParam is_bad boolean No-example
   * @bodyParam bonus_points int No-example
   * @bodyParam birthday date No-example
   * @bodyParam gender string Example:male,female
   * @bodyParam is_active boolean No-example
   * @bodyParam carrier_email string Example:aa@aa.com
   * @bodyParam carrier_phone string Example:0900000000
   * @bodyParam carrier_certificate string Example:123456789
   * @bodyParam subscribe_start_at datetime No-example
   * @bodyParam subscribe_end_at datetime No-example
   * @bodyParam color string #000000
   * @bodyParam acumatica_id string No-example
   */
  public function update(Request $request, $id)
  {
    return ModelHelper::ws_UpdateHandler($this, $request, $id);
  }

  /**
   * Delete
   *
   * @urlParam  user required The ID of user. Example: 2
   */
  public function destroy($id)
  {
    return ModelHelper::ws_DestroyHandler($this, $id);
  }

  /**
   * Bad
   *
   * @urlParam  user required The ID of user. Example: 2
   */
  public function bad($id)
  {
    $model         = $this->model::find($id);
    $model->is_bad = 1;
    $model->save();
    return response()->json([
      'message' => 'marked as bad',
    ]);

  }

  /**
   * NotBad
   *
   * @urlParam  user required The ID of user. Example: 2
   */
  public function notbad($id)
  {
    $model         = $this->model::find($id);
    $model->is_bad = 0;
    $model->save();
    return response()->json([
      'message' => 'marked as notbad',
    ]);
  }

  /**
   * Send ResetPassword Mail
   *
   */
  public function reset_password_mail($id)
  {
    $model = $this->model::find($id);
    $res   = Http::withHeaders([])->post(config('stone.web_api_url') . '/api/auth/forgetpassword/request', [
      "email" => $model->email,
    ]);
    if ($res->status() == '200') {
      return response()->json([
        'message' => 'reset password mail sent.',
      ]);
    } else {
      return response()->json([
        'message' => 'reset password mail request fail.',
      ], 400);
    }
  }

  /**
   * Export Excel Signedurl
   *
   */
  public function export_excel_signedurl(Request $request)
  {
    $users = $request->has('users') ? $request->users : null;
    return URL::temporarySignedRoute(
      'user_export_excel',
      now()->addMinutes(30),
      ['users' => $users]
    );
  }

  /**
   * Export Excel
   *
   */
  public function export_excel(Request $request)
  {
    $users = $request->has('users') ? $request->users : null;
    return Excel::download(new UserExport($users), 'user.xlsx');
  }
}
