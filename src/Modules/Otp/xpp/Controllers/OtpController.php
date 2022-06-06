<?php

namespace Wasateam\Laravelapistone\Modules\Otp\App\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Wasateam\Laravelapistone\Helpers\ModelHelper;
use Wasateam\Laravelapistone\Models\Otp;

/**
 * @group Otp 簡訊開發者
 *
 *
 * @authenticated
 *
 * is_active 是否啟用
 * content 內容
 * usage 用途
 * user 使用者
 * expired_at 過期時間
 *
 */
class OtpController extends Controller
{
  public $model        = 'Wasateam\Laravelapistone\Modules\Otp\App\Models\Otp';
  public $name         = 'otp';
  public $resource     = 'Wasateam\Laravelapistone\Modules\Otp\App\Http\Resources\Otp';
  public $input_fields = [
    'is_active',
    'content',
    'usage',
    'expired_at',
  ];
  public $belongs_to = [
    'user',
  ];
  public $order_fields = [
    'updated_at',
    'created_at',
  ];

  public function __construct()
  {
    if (config('stone.country_code')) {
    }
  }

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
   * Show
   *
   * @urlParam  otp required The ID of otp. Example: 1
   */
  public function show(Request $request, $id = null)
  {
    return ModelHelper::ws_ShowHandler($this, $request, $id);
  }
}
