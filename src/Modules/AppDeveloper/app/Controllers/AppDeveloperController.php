<?php

namespace Wasateam\Laravelapistone\Modules\AppDeveloper\App\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Wasateam\Laravelapistone\Helpers\ModelHelper;
use Wasateam\Laravelapistone\Models\AppDeveloper;

/**
 * @group AppDeveloper 簡訊開發者
 *
 *
 * @authenticated
 *
 * is_active 是否啟用
 * mobile 手機
 * mobile_country_code 手機國碼
 * otp 永久性OTP
 *
 *
 */
class AppDeveloperController extends Controller
{
  public $model        = 'Wasateam\Laravelapistone\Modules\AppDeveloper\App\Models\AppDeveloper';
  public $name         = 'app_developer';
  public $resource     = 'Wasateam\Laravelapistone\Modules\AppDeveloper\App\Http\Resources\AppDeveloper';
  public $input_fields = [
    'is_active',
    'mobile',
    'mobile_country_code',
    'otp',
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
   * Store
   *
   * @bodyParam is_active 是否啟用 boolean Example:1
   * @bodyParam mobile 手機 string Example: 999555666
   * @bodyParam mobile_country_code 手機國碼 string Example: 886
   * @bodyParam otp 永久性OTP string Example: 1234
   */
  public function store(Request $request, $id = null)
  {
    return ModelHelper::ws_StoreHandler($this, $request, $id);
  }

  /**
   * Show
   *
   * @urlParam  app_developer required The ID of app_developer. Example: 1
   */
  public function show(Request $request, $id = null)
  {
    return ModelHelper::ws_ShowHandler($this, $request, $id);
  }

  /**
   * Update
   *
   * @urlParam  app_developer required The ID of app_developer. Example: 1
   * @bodyParam is_active 是否啟用 boolean Example:1
   * @bodyParam mobile 手機 string Example: 999555666
   * @bodyParam mobile_country_code 手機國碼 string Example: 886
   * @bodyParam otp 永久性OTP string Example: 1234
   */
  public function update(Request $request, $id)
  {
    return ModelHelper::ws_UpdateHandler($this, $request, $id);
  }

  /**
   * Delete
   *
   * @urlParam  app_developer required The ID of app_developer. Example: 2
   */
  public function destroy($id)
  {
    return ModelHelper::ws_DestroyHandler($this, $id);
  }
}
