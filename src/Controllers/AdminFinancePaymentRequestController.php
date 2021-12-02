<?php

namespace Wasateam\Laravelapistone\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Wasateam\Laravelapistone\Helpers\ModelHelper;

/**
 * @group AdminFinancePaymentRequest
 *
 * @authenticated
 *
 * admin 人員
 * status 狀態
 * ~ 申請中
 * ~ 審核通過待付款
 * ~ 完成
 * ~ 審核不通過
 * invoice_type
 * ~ paper 紙本發票
 * ~ electronic 電子發票
 * ~ international 國際Invoice
 * paying_type
 * ~ cash 現金
 * ~ credit_card 信用卡
 * amount 金額
 * paying_date 付款日期
 * verify_date 認證日期
 * complete_date 撥款日期
 * request_remark 請款備註
 * payload
 * reviewer 覆核人
 * review_remark 覆核備註
 */
class AdminFinancePaymentRequestController extends Controller
{
  public $model        = 'Wasateam\Laravelapistone\Models\AdminFinancePaymentRequest';
  public $name         = 'admin_finance_fayment_request';
  public $resource     = 'Wasateam\Laravelapistone\Resources\AdminFinancePaymentRequest';
  public $input_fields = [
    'status',
    'invoice_type',
    'paying_type',
    'amount',
    'paying_date',
    'verify_date',
    'complete_date',
    'request_remark',
    'payload',
    'review_remark',
  ];
  public $belongs_to = [
    'admin',
    'reviewer',
  ];
  public $order_by     = 'created_at';
  public $order_way    = 'desc';
  public $order_fields = [
    'updated_at',
    'created_at',
    'paying_date',
    'verify_date',
    'complete_date',
  ];

  /**
   * Index
   * @queryParam search string No-example
   * @queryParam admin id Example:1,2,3
   * @queryParam reviewer id Example:1,2,3
   *
   */
  public function index(Request $request, $id = null)
  {
    return ModelHelper::ws_IndexHandler($this, $request, $id);
  }

  /**
   * Store
   *
   * @bodyParam status string No-example
   * @bodyParam invoice_type string No-example
   * @bodyParam paying_type string No-example
   * @bodyParam amount string No-example
   * @bodyParam paying_date date No-example
   * @bodyParam verify_date date No-example
   * @bodyParam complete_date date No-example
   * @bodyParam request_remark string No-example
   * @bodyParam payload object No-example
   * @bodyParam review_remark string No-example
   * @bodyParam admin id No-example
   * @bodyParam reviewer id No-example
   */
  public function store(Request $request, $id = null)
  {
    return ModelHelper::ws_IndexHandler($this, $request, $id);
  }

  /**
   * Show
   *
   * @urlParam  admin_finance_fayment_request required The ID of admin_finance_fayment_request. Example: 1
   */
  public function show(Request $request, $id = null)
  {
    return ModelHelper::ws_ShowHandler($this, $request, $id);
  }

  /**
   * Update
   *
   * @urlParam  admin_finance_fayment_request required The ID of admin_finance_fayment_request. Example: 1
   * @bodyParam status string No-example
   * @bodyParam invoice_type string No-example
   * @bodyParam paying_type string No-example
   * @bodyParam amount string No-example
   * @bodyParam paying_date date No-example
   * @bodyParam verify_date date No-example
   * @bodyParam complete_date date No-example
   * @bodyParam request_remark string No-example
   * @bodyParam payload object No-example
   * @bodyParam review_remark string No-example
   * @bodyParam admin id No-example
   * @bodyParam reviewer id No-example
   */
  public function update(Request $request, $id)
  {
    return ModelHelper::ws_UpdateHandler($this, $request, $id);
  }

  /**
   * Delete
   *
   * @urlParam  admin_finance_fayment_request required The ID of admin_finance_fayment_request. Example: 2
   */
  public function destroy($id)
  {
    return ModelHelper::ws_DestroyHandler($this, $id);
  }
}
