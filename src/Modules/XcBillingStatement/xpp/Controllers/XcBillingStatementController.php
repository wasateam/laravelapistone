<?php

namespace Wasateam\Laravelapistone\Modules\XcBillingStatement\App\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Wasateam\Laravelapistone\Helpers\ModelHelper;
use Wasateam\Laravelapistone\Models\XcBillingStatement;

/**
 * @group XcBillingStatement 請款
 *
 * @authenticated
 *
 * invoice_type 發票類型
 * ~ 0 紙本發票
 * ~ 1 電子發票
 * ~ 2 Invoice
 * images 圖片
 * pay_type 付款類型
 * ~ 0 現金
 * ~ 1 信用卡-需附明細截圖
 * amount 金額
 * pay_at 付款時間
 * review_at 覆核時間
 * remark 備註
 * review_status
 * ~ 0 申請中
 * ~ 1 OK，待付款
 * ~ 2 打妹
 * ~ 3 已付款
 * admin 申請人
 * reviewer 覆核人
 *
 */
class XcBillingStatementController extends Controller
{
  public $model        = 'Wasateam\Laravelapistone\Modules\XcBillingStatement\App\Models\XcBillingStatement';
  public $name         = 'xc_billing_statement';
  public $resource     = 'Wasateam\Laravelapistone\Modules\XcBillingStatement\App\Http\Resources\XcBillingStatement';
  public $input_fields = [
    'invoice_type',
    'images',
    'pay_type',
    'amount',
    // 'pay_at',
    // 'review_at',
    'remark',
    // 'review_status',
  ];
  public $belongs_to = [
    // 'admin',
    // 'reviewer',
  ];
  public $order_fields = [
    'updated_at',
    'created_at',
  ];

  public function __construct()
  {
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
   * @urlParam  xc_billing_statement required The ID of xc_billing_statement. Example: 1
   */
  public function show(Request $request, $id = null)
  {
    return ModelHelper::ws_ShowHandler($this, $request, $id);
  }

  /**
   * Update
   *
   * @urlParam  xc_billing_statement required The ID of xc_billing_statement. Example: 1
   * invoice_type 發票類型 integer Example: 0
   * images 圖片 No-example
   * pay_type 付款類型 integer Example: 0
   * amount 金額 integer Example: 5566
   * pay_at 付款時間 datetime No-example
   * review_at 覆核時間 datetime No-example
   * remark 備註 string No-example
   * review_status 覆核狀態 Example: 0
   * admin 申請人 Example:1
   * reviewer 覆核人 Example:1
   */
  public function update(Request $request, $id)
  {
    return ModelHelper::ws_UpdateHandler($this, $request, $id);
  }

  /**
   * Delete
   *
   * @urlParam  xc_billing_statement required The ID of xc_billing_statement. Example: 2
   */
  public function destroy($id)
  {
    return ModelHelper::ws_DestroyHandler($this, $id);
  }

  /**
   * Index Admin My
   * @queryParam search string No-example
   *
   */
  public function index_admin_my(Request $request, $id = null)
  {
    return ModelHelper::ws_IndexHandler($this, $request, $id, true, function ($snap) {
      return $snap->where('admin_id', \Auth::user()->id);
    });
  }

  /**
   * Store Admin My
   *
   * invoice_type 發票類型 integer Example: 0
   * images 圖片 No-example
   * pay_type 付款類型 integer Example: 0
   * amount 金額 integer Example: 5566
   * remark 備註 string No-example
   */
  public function store_admin_my(Request $request, $id = null)
  {
    return ModelHelper::ws_StoreHandler($this, $request, $id, function ($model) {
      $model->admin_id = \Auth::user()->id;
      $model->save();
    });
  }
}
