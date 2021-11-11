<?php

namespace Wasateam\Laravelapistone\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Wasateam\Laravelapistone\Helpers\ModelHelper;
use Wasateam\Laravelapistone\Helpers\ShopHelper;

class ShopOrderController extends Controller
{
  public $model                   = 'Wasateam\Laravelapistone\Models\ShopOrder';
  public $name                    = 'shop_order';
  public $resource                = 'Wasateam\Laravelapistone\Resources\ShopOrder';
  public $resource_for_collection = 'Wasateam\Laravelapistone\Resources\ShopProductCollection';
  public $input_fields            = [
    'type',
    'orderer',
    'orderer_tel',
    'orderer_birthday',
    'orderer_email',
    'orderer_gender',
    'receiver',
    'receiver_tel',
    'receiver_email',
    'receiver_gender',
    'receiver_birthday',
    'receive_address',
    'receive_remark',
    'package_way',
    'status',
    'status_remark',
    'receive_way',
    'ship_way',
    'delivery_date',
    'ship_start_time',
    'ship_end_time',
    'ship_remark',
    'ship_date',
    'ship_status',
    'customer_service_remark',
    'pay_type',
    // 'pay_status',
    // 'discounts',
    // 'freight',
    // 'products_price',
    // 'order_price',
    'receipt_number',
    'receipt_status',
    'receipt_type',
    'receipt_carrier_number',
    'receipt_tax',
    'receipt_title',
  ];
  public $search_fields = [
  ];
  public $filter_fields = [
  ];
  public $belongs_to = [
    'user',
    'user_address',
    'shop_ship_area_setting',
    'shop_ship_time_setting',
    'area',
    'area_section',
  ];
  public $order_fields = [
    'updated_at',
    'created_at',
  ];
  public $uuid = false;

  public function __construct()
  {
    if (config('stone.shop.uuid')) {
      $this->uuid = true;
    }
    if (config('stone.mode') == 'cms') {
      $this->input_fields[] = 'pay_status';
      $this->input_fields[] = 'discounts';
      $this->input_fields[] = 'freight';
      $this->input_fields[] = 'products_price';
      $this->input_fields[] = 'order_price';
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
   * @bodyParam type string 訂單類型 No-example
   * @bodyParam orderer string 訂購者 No-example
   * @bodyParam orderer_tel string 訂購者電話 No-example
   * @bodyParam orderer_birthday string 訂購者生日 No-example
   * @bodyParam orderer_email string 訂購者mail No-example
   * @bodyParam orderer_gender string 訂購者性別 No-example
   * @bodyParam receiver string 收件者 No-example
   * @bodyParam receiver_tel string 收件人電話 No-example
   * @bodyParam receiver_email string 收件人mail No-example
   * @bodyParam receiver_gender string 收件人性別 No-example
   * @bodyParam receiver_birthday string 收件人生日 No-example
   * @bodyParam receive_address string 收件人住址 No-example
   * @bodyParam receive_remark text 收件備註 No-example
   * @bodyParam package_way text 包裝方式 No-example
   * @bodyParam status text 訂單狀態 No-example
   * @bodyParam status_remark text 狀態備註 No-example
   * @bodyParam receive_way text 收貨方式 No-example
   * @bodyParam ship_way text 配送方式 No-example
   * @bodyParam delivery_date text 配送日期 No-example
   * @bodyParam ship_start_time text 出貨開始時間 No-example
   * @bodyParam ship_end_time text 出貨結束時間 No-example
   * @bodyParam ship_remark text 出貨備註 No-example
   * @bodyParam ship_date text 出貨日期 No-example
   * @bodyParam ship_status text 出貨狀態 No-example
   * @bodyParam customer_service_remark text 客服備註 No-example
   * @bodyParam pay_type text  付款方式 No-example
   * @bodyParam pay_status text  付款狀態 No-example
   * @bodyParam discounts text  優惠活動 No-example
   * @bodyParam freight text 運費  No-example
   * @bodyParam products_price text  商品總金額 No-example
   * @bodyParam order_price text 訂單金額  No-example
   * @bodyParam receipt_number text  發票號碼 No-example
   * @bodyParam receipt_status text  發票狀態 No-example
   * @bodyParam receipt_type text  發票形式 No-example
   * @bodyParam receipt_carrier_number text 發票載具編號  No-example
   * @bodyParam receipt_tax text  統一編號  No-example
   * @bodyParam receipt_title text  抬頭 No-example
   *
   */
  public function store(Request $request, $id = null)
  {
    return ModelHelper::ws_StoreHandler($this, $request, $id, function ($model) {
      ShopHelper::changeShopOrderPrice($model->id);
    });
  }

  /**
   * Show
   *
   * @urlParam  shop_order required The ID of shop_order. Example: 1
   */
  public function show(Request $request, $id = null)
  {
    return ModelHelper::ws_ShowHandler($this, $request, $id);
  }

  /**
   * update
   *
   * @urlParam  shop_order required The ID of shop_order. Example: 1
   * @bodyParam type string 訂單類型 No-example
   * @bodyParam orderer string 訂購者 No-example
   * @bodyParam orderer_tel string 訂購者電話 No-example
   * @bodyParam orderer_birthday string 訂購者生日 No-example
   * @bodyParam orderer_email string 訂購者mail No-example
   * @bodyParam orderer_gender string 訂購者性別 No-example
   * @bodyParam receiver string 收件者 No-example
   * @bodyParam receiver_tel string 收件人電話 No-example
   * @bodyParam receiver_email string 收件人mail No-example
   * @bodyParam receiver_gender string 收件人性別 No-example
   * @bodyParam receiver_birthday string 收件人生日 No-example
   * @bodyParam receive_address string 收件人住址 No-example
   * @bodyParam receive_remark text 收件備註 No-example
   * @bodyParam package_way text 包裝方式 No-example
   * @bodyParam status text 訂單狀態 No-example
   * @bodyParam status_remark text 狀態備註 No-example
   * @bodyParam receive_way text 收貨方式 No-example
   * @bodyParam ship_way text 配送方式 No-example
   * @bodyParam delivery_date text 配送日期 No-example
   * @bodyParam ship_start_time text 出貨開始時間 No-example
   * @bodyParam ship_end_time text 出貨結束時間 No-example
   * @bodyParam ship_remark text 出貨備註 No-example
   * @bodyParam ship_date text 出貨日期 No-example
   * @bodyParam ship_status text 出貨狀態 No-example
   * @bodyParam customer_service_remark text 客服備註 No-example
   * @bodyParam pay_type text  付款方式 No-example
   * @bodyParam pay_status text  付款狀態 No-example
   * @bodyParam discounts text  優惠活動 No-example
   * @bodyParam freight text 運費  No-example
   * @bodyParam products_price text  商品總金額 No-example
   * @bodyParam order_price text 訂單金額  No-example
   * @bodyParam receipt_number text  發票號碼 No-example
   * @bodyParam receipt_status text  發票狀態 No-example
   * @bodyParam receipt_type text  發票形式 No-example
   * @bodyParam receipt_carrier_number text 發票載具編號  No-example
   * @bodyParam receipt_tax text  統一編號  No-example
   * @bodyParam receipt_title text  抬頭 No-example
   *
   */
  public function update(Request $request, $id)
  {
    return ModelHelper::ws_UpdateHandler($this, $request, $id, [], function ($model) {
      ShopHelper::changeShopOrderPrice($model->id);
    });
  }

  /**
   * Delete
   *
   * @urlParam  shop_order required The ID of shop_order. Example: 2
   */
  public function destroy($id)
  {
    return ModelHelper::ws_DestroyHandler($this, $id);
  }

  /**
   * Export Excel Signedurl
   *
   */
  // public function export_excel_signedurl(Request $request)
  // {
  //   $shop_orders = $request->has('shop_orders') ? $request->shop_orders : null;
  //   return URL::temporarySignedRoute(
  //     'user_export_excel',
  //     now()->addMinutes(30),
  //     ['shop_orders' => $shop_orders]
  //   );
  // }

  /**
   * Export Excel
   *
   */
  // public function export_excel(Request $request)
  // {
  //   $shop_orders = $request->has('shop_orders') ? $request->shop_orders : null;
  //   return Excel::download(new UserExport($shop_orders), 'shop_orders.xlsx');
  // }
}
