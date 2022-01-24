<?php

namespace Wasateam\Laravelapistone\Controllers;

use App\Http\Controllers\Controller;
use Wasateam\Laravelapistone\Helpers\LinePayHelper;

/**
 * @group LinePay LINE Pay 相關動作
 *
 * @authenticated
 */
class LinePayController extends Controller
{

  /**
   * 確認付款
   */
  public function payment_confirm($transaction_id)
  {
    LinePayHelper::payment_confirm($transaction_id);
  }

  /**
   * 取消付款
   */
  public function payment_cancel($transaction_id)
  {
    LinePayHelper::payment_cancel($transaction_id);
  }
}
