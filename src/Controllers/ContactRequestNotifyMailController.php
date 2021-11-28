<?php

namespace Wasateam\Laravelapistone\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Wasateam\Laravelapistone\Helpers\ModelHelper;

/**
 * @group  ContactRequestNotifyMail
 *
 * name 人員名稱
 * is_active fu3
 * email 信箱
 * remark 備註
 * country_code 國家代碼
 *
 * @authenticated
 */
class ContactRequestNotifyMailController extends Controller
{
  public $model        = 'Wasateam\Laravelapistone\Models\ContactRequestNotifyMail';
  public $name         = 'contact_request_notify_mail';
  public $resource     = 'Wasateam\Laravelapistone\Resources\ContactRequestNotifyMail';
  public $input_fields = [
    'is_active',
    'name',
    'mail',
    'remark',
  ];
  public $order_fields = [
    'created_at',
  ];
  public $filter_fields = [];
  public $order_by      = 'created_at';
  public $order_way     = 'desc';
  public $country_code  = true;

  public function __construct()
  {
    if (config('stone.country_code')) {
      $this->input_fields[]  = 'country_code';
      $this->filter_fields[] = 'country_code';
    }
  }

  /**
   * Index
   *
   */
  public function index(Request $request, $id = null)
  {
    return ModelHelper::ws_IndexHandler($this, $request, $id);
  }

  /**
   * Store
   *
   * @bodyParam name string Example: davidturtle
   * @bodyParam is_active boolean Example: 1
   * @bodyParam email string Example: davidturtle0313@gmail.com
   * @bodyParam remark string Example: Remark here
   * @bodyParam country_code string No-example
   */
  public function store(Request $request, $id = null)
  {
    return ModelHelper::ws_StoreHandler($this, $request, $id);
  }

  /**
   * Show
   *
   * @urlParam  contact_request_notify_mail required The ID of contact_request_notify_mail. Example: 1
   */
  public function show(Request $request, $id = null)
  {
    return ModelHelper::ws_ShowHandler($this, $request, $id);
  }

  /**
   * Update
   *
   * @urlParam contact_request_notify_mail Example: 1
   * @bodyParam name string Example: davidturtle
   * @bodyParam is_active boolean Example: 1
   * @bodyParam email string Example: davidturtle0313@gmail.com
   * @bodyParam remark string Example: Remark here
   * @bodyParam country_code string No-example
   */
  public function update(Request $request, $id)
  {
    return ModelHelper::ws_UpdateHandler($this, $request, $id);
  }

  /**
   * Delete
   *
   * @urlParam contact_request_notify_mail Example: 2
   */
  public function destroy($id)
  {
    return ModelHelper::ws_DestroyHandler($this, $id);
  }
}
