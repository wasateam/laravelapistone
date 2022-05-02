<?php

namespace Wasateam\Laravelapistone\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Wasateam\Laravelapistone\Helpers\ModelHelper;
use Wasateam\Laravelapistone\Models\InvoiceJob;

/**
 * @group InvoiceJob
 *
 *
 * invoice_date 開立日期
 * status 狀態
 * ~ waiting
 * ~ invoiced
 * shop_order 訂單
 *
 */
class InvoiceJobController extends Controller
{
  public $model        = 'Wasateam\Laravelapistone\Models\InvoiceJob';
  public $name         = 'invoice_job';
  public $resource     = 'Wasateam\Laravelapistone\Resources\InvoiceJob';
  public $input_fields = [
    'invoice_date',
    'status',
  ];
  public $belongs_to = [
    'shop_order',
  ];
  public $order_fields = [
    'updated_at',
    'created_at',
  ];
  public $filter_fields = [
    'status',
  ];
  public $filter_time_fields = [
    'invoice_date',
  ];

  public function __construct()
  {
  }

  /**
   * Index
   * @queryParam search string No-example
   * @queryParam invoice_date date Example: 2022-10-10
   * @queryParam status string Example: waiting
   * @queryParam shop_order id Example: 1
   *
   */
  public function index(Request $request, $id = null)
  {
    return ModelHelper::ws_IndexHandler($this, $request, $id, true);
  }

  /**
   * Store
   *
   * @bodyParam invoice_date date Example: 2022-03-03
   * @bodyParam status string Example: waiting
   * @bodyParam shop_order id Example: 1
   */
  public function store(Request $request, $id = null)
  {
    return ModelHelper::ws_StoreHandler($this, $request, $id);
  }

  /**
   * Show
   *
   * @urlParam  invoice_job required The ID of invoice_job. Example: 1
   */
  public function show(Request $request, $id = null)
  {
    return ModelHelper::ws_ShowHandler($this, $request, $id);
  }

  /**
   * Update
   *
   * @urlParam  invoice_job required The ID of invoice_job. Example: 1
   * @bodyParam invoice_date date Example: 2022-03-03
   * @bodyParam status string Example: waiting
   * @bodyParam shop_order id Example: 1
   */
  public function update(Request $request, $id)
  {
    return ModelHelper::ws_UpdateHandler($this, $request, $id);
  }

  /**
   * Delete
   *
   * @urlParam  invoice_job required The ID of invoice_job. Example: 2
   */
  public function destroy($id)
  {
    return ModelHelper::ws_DestroyHandler($this, $id);
  }
}
