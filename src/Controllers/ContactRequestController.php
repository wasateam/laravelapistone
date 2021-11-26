<?php

namespace Wasateam\Laravelapistone\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Wasateam\Laravelapistone\Helpers\EmailHelper;
use Wasateam\Laravelapistone\Helpers\ModelHelper;
use Wasateam\Laravelapistone\Models\ContactRequestNotifyMail;

/**
 * @group  ContactRequest
 *
 * name 名稱
 * email 信箱
 * tel 電話
 * remark 說明
 * company_name 公司名稱
 * budget 預算
 * payload Payload隨便放
 * ip 來源IP
 * country_code 國家代碼
 *
 * @authenticated
 */
class ContactRequestController extends Controller
{
  public $model        = 'Wasateam\Laravelapistone\Models\ContactRequest';
  public $name         = 'contact_request';
  public $resource     = 'Wasateam\Laravelapistone\Resources\ContactRequest';
  public $input_fields = [
    "name",
    "email",
    "tel",
    "remark",
    "company_name",
    "budget",
    "payload",
    "ip",
  ];
  public $order_fields = [
    'created_at',
  ];
  public $filter_fields = [];
  public $order_by      = 'created_at';
  public $order_way     = 'desc';

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
    if (config('stone.mode') == 'cms') {
      return ModelHelper::ws_IndexHandler($this, $request, $id);
    } else if (config('stone.mode') == 'webapi') {
      return ModelHelper::ws_IndexHandler($this, $request, $id);
    }
  }

  /**
   * Store
   *
   * @bodyParam type string Example: a
   * @bodyParam name string Example: davidturtle
   * @bodyParam email string Example: davidturtle0313@gmail.com
   * @bodyParam tel string Example: 0987462600
   * @bodyParam remark string Example: Remark here
   * @bodyParam company_name string Example: Wasateam.co
   * @bodyParam budget string Example: nolimit
   * @bodyParam payload object No-example
   * @bodyParam country_code string No-example
   */
  public function store(Request $request, $id = null)
  {
    if (config('stone.mode') == 'cms') {
      return ModelHelper::ws_StoreHandler($this, $request, $id);
    } else if (config('stone.mode') == 'webapi') {
      return ModelHelper::ws_StoreHandler($this, $request, $id, function ($model) {
        $model->ip = \Request::ip();
        $model->save();
        $notify_emails = [];
        $notifies;
        if (config('stone.country_code')) {
          if ($model->country_code) {
            $notifies = ContactRequestNotifyMail::where('country_code', $model->country_code)->get();
          } else {
            $notifies = ContactRequestNotifyMail::whereNull('country_code')->get();
          }
        } else {
          $notifies = ContactRequestNotifyMail::all();
        }
        if ($notifies) {
          foreach ($notifies as $notify) {
            $notify_emails[] = $notify->mail;
          }
        }
        EmailHelper::notify_contact_request($model, $notify_emails);
        if (config('stone.contact_request.auto_reply')) {
          EmailHelper::contact_request_auto_reply($model);
        }
      });
    }
  }

  /**
   * Show
   *
   * @urlParam  contact_request required The ID of contact_request. Example: 1
   */
  public function show(Request $request, $id = null)
  {
    return ModelHelper::ws_ShowHandler($this, $request, $id);
  }

  /**
   * Update
   *
   * @urlParam contact_request Example: 1
   * @bodyParam type string Example: a
   * @bodyParam name string Example: davidturtle
   * @bodyParam email string Example: davidturtle0313@gmail.com
   * @bodyParam tel string Example: 0987462600
   * @bodyParam remark string Example: Remark here
   * @bodyParam company_name string Example: Wasateam.co
   * @bodyParam budget string Example: nolimit
   * @bodyParam payload object No-example
   * @bodyParam country_code string No-example
   */
  public function update(Request $request, $id)
  {
    if (config('stone.mode') == 'cms') {
      return ModelHelper::ws_UpdateHandler($this, $request, $id);
    } else if (config('stone.mode') == 'webapi') {
      return ModelHelper::ws_UpdateHandler($this, $request, $id, [], function ($model) {
        $model->ip = \Request::ip();
        $model->save();
      });
    }
  }

  /**
   * Delete
   *
   * @urlParam contact_request Example: 2
   */
  public function destroy($id)
  {
    if (config('stone.mode') == 'cms') {
      return ModelHelper::ws_DestroyHandler($this, $id);
    } else if (config('stone.mode') == 'webapi') {
      return null;
    }
  }
}
