<?php

namespace Wasateam\Laravelapistone\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\URL;
use Maatwebsite\Excel\Facades\Excel;
use Wasateam\Laravelapistone\Helpers\AuthHelper;
use Wasateam\Laravelapistone\Helpers\ModelHelper;
use Wasateam\Laravelapistone\Helpers\UserHelper;

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
 * invite_no 邀請碼
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
    'customer_id',
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
    return ModelHelper::ws_IndexHandler($this, $request, $id, false, function ($snap) use ($request) {
      if (config('stone.user.subscribe')) {
        if ($request->has('subscribe_status')) {
          $subscribe_status = $request->subscribe_status;
          if ($subscribe_status == 'unsubscribe') {
            $snap = $snap
              ->whereNull('subscribe_start_at')
              ->whereNull('subscribe_end_at');
          } else if ($subscribe_status == 'subscribing') {
            $today_datetime = Carbon::now();
            $snap           = $snap
              ->where('subscribe_start_at', '<=', $today_datetime)
              ->where('subscribe_end_at', '>=', $today_datetime);
          } else if ($subscribe_status == 'subscribe-expired') {
            $today_datetime = Carbon::now();
            $snap           = $snap
              ->where('subscribe_end_at', '<', $today_datetime);
          }
        }
      }
      return $snap;
    });
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
      if (config('stone.user.invite')) {
        UserHelper::generateInviteNo($model, $this->model);
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
    return ModelHelper::ws_ExportExcelSignedurlHandler($this, $request);
  }

  /**
   * Export Excel
   *
   */
  public function export_excel(Request $request)
  {
    $headings = [];
    if (config('stone.user.export.uuid')) {
      $headings[] = "會員UUID";
    }
    if (
      config('stone.user.export.customer_id') &&
      config('stone.user.customer_id')
    ) {
      $headings[] = "會員編號";
    }
    if (config('stone.user.export.name')) {
      $headings[] = "名稱";
    }
    if (config('stone.user.export.country')) {
      $headings[] = "國家/地區";
    }
    if (config('stone.user.export.address_mailing')) {
      $headings[] = "縣市";
      $headings[] = "行政區";
      $headings[] = "地址";
    }
    if (config('stone.user.export.gender')) {
      $headings[] = "性別";
    }
    if (config('stone.user.export.email')) {
      $headings[] = "Email";
    }
    if (config('stone.user.export.tel')) {
      $headings[] = "電話";
    }
    if (config('stone.user.export.birthday')) {
      $headings[] = "生日";
    }
    if (config('stone.user.export.description')) {
      $headings[] = "介紹";
    }
    if (config('stone.user.export.updated_at')) {
      $headings[] = "最後更新時間";
    }
    if (
      config('stone.user.export.is_bad') &&
      config('stone.user.is_bad')
    ) {
      $headings[] = "黑名單";
    }
    if (
      config('stone.user.export.bonus_points') &&
      config('stone.user.bonus_points')
    ) {
      $headings[] = "紅利點數";
    }
    if (
      config('stone.user.export.subscribe') &&
      config('stone.user.subscribe')
    ) {
      $headings[] = "訂閱狀態";
    }
    if (config('stone.user.export.shop_sum')) {
      $headings = UserHelper::setUserExportHeadingsShopSum($headings);
    }
    if (config('stone.user.export.shop_count')) {
      $headings = UserHelper::setUserExportHeadingsShopCount($headings);
    }
    if (config('stone.user.export.invite_count')) {
      $headings[] = "邀請數量";
    }
    if (config('stone.user.export.created_at')) {
      $headings[] = "加入時間";
    }
    if (config('stone.user.export.created_at_year')) {
      $headings[] = "(加入)年";
    }
    if (config('stone.user.export.created_at_month')) {
      $headings[] = "(加入)月";
    }
    if (config('stone.user.export.created_at_day')) {
      $headings[] = "(加入)日";
    }
    return ModelHelper::ws_ExportExcelHandler(
      $this,
      $request,
      $headings,
      function ($model) use ($request) {
        $created_at = Carbon::parse($model->created_at);
        $updated_at = Carbon::parse($model->updated_at)->format('Y-m-d');
        $locale     = 'zh_tw';
        if ($request->filled('locale')) {
          $locale = $request->locale;
        } else if (config('stone.locale') && config('stone.locale.default')) {
          $locale = config('stone.locale.default');
        }

        $map = [];
        if (config('stone.user.export.uuid')) {
          $map[] = $model->uuid;
        }
        if (
          config('stone.user.export.customer_id') &&
          config('stone.user.customer_id')
        ) {
          $map[] = $model->customer_id;
        }
        if (config('stone.user.export.name')) {
          $map[] = $model->name;
        }
        if (config('stone.user.export.country')) {
          $map[] = "台灣";
        }
        if (config('stone.user.export.address_mailing')) {
          $map = UserHelper::setUserExportMapAddressMailing($map, $model);
        }
        if (config('stone.user.export.gender')) {
          $gender = $model->gender;
          $map[]  = $gender ? __("wasateam::messages.{$gender}", [], $locale) : '';
        }
        if (config('stone.user.export.email')) {
          $map[] = $model->email;
        }
        if (config('stone.user.export.tel')) {
          $map[] = $model->tel;
        }
        if (config('stone.user.export.birthday')) {
          $map[] = $model->birthday;
        }
        if (config('stone.user.export.description')) {
          $map[] = $model->description;
        }
        if (config('stone.user.export.updated_at')) {
          $map[] = $updated_at;
        }

        if (
          config('stone.user.export.is_bad') &&
          config('stone.user.is_bad')
        ) {
          $map[] = $model->is_bad;
        }
        if (
          config('stone.user.export.bonus_points') &&
          config('stone.user.bonus_points')
        ) {
          $map[] = $model->bonus_points;
        }
        if (
          config('stone.user.export.subscribe') &&
          config('stone.user.subscribe')
        ) {
          $today_datetime = Carbon::now();
          $subscribe      = '未訂閱';
          if (!$model->subscribe_start_at && !$model->subscribe_end_at) {
            $subscribe = '未訂閱';
          } else if ($model->subscribe_start_at && $model->subscribe_end_at) {
            $subscribe_start_at_datetime = Carbon::parse($model->subscribe_start_at);
            $subscribe_end_at_datetime   = Carbon::parse($model->subscribe_end_at);
            if (
              $today_datetime->greaterThanOrEqualTo($subscribe_start_at_datetime) &&
              $today_datetime->lessThanOrEqualTo($subscribe_end_at_datetime)
            ) {
              $subscribe = '訂閱中';
            }
            if ($today_datetime->greaterThanOrEqualTo($subscribe_end_at_datetime)) {
              $subscribe = '訂閱逾期';
            }
          }
          $map[] = $subscribe;
        }

        if (config('stone.user.export.shop_sum')) {
          $map = UserHelper::setUserExportMapShopSum($map, $model);
        }
        if (config('stone.user.export.shop_count')) {
          $map = UserHelper::setUserExportMapShopCount($map, $model);
        }
        if (config('stone.user.export.invite_count')) {
          $map[] = UserHelper::getUserInvitedCount($model);
        }
        if (config('stone.user.export.created_at')) {
          $map[] = $created_at->format('Y-m-d');
        }
        if (config('stone.user.export.created_at_year')) {
          $map[] = $created_at->format('Y');
        }
        if (config('stone.user.export.created_at_month')) {
          $map[] = $created_at->format('m');
        }
        if (config('stone.user.export.created_at_day')) {
          $map[] = $created_at->format('d');
        }

        return $map;
      }
    );
  }
}
