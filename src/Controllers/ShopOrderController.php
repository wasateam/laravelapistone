<?php

namespace Wasateam\Laravelapistone\Controllers;

use App\Http\Controllers\Controller;
use Auth;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Maatwebsite\Excel\Facades\Excel;
use Wasateam\Laravelapistone\Exports\ShopOrderExport;
use Wasateam\Laravelapistone\Helpers\EcpayHelper;
use Wasateam\Laravelapistone\Helpers\ModelHelper;
use Wasateam\Laravelapistone\Helpers\ShopHelper;
use Wasateam\Laravelapistone\Models\ShopCartProduct;
use Wasateam\Laravelapistone\Models\ShopOrder;
use Wasateam\Laravelapistone\Models\ShopOrderShopProduct;
use Wasateam\Laravelapistone\Models\ShopShipTimeSetting;

/**
 * @group 訂單
 *
 * type 類型
 * order_type 訂單類型
 * no 訂單編號
 * orderer 訂購人
 * orderer_tel 訂購人電話
 * orderer_birthday 訂購人生日
 * orderer_email 訂購人信箱
 * orderer_gender 訂購人性別
 * ~ female: 女森
 * ~ male: 男森
 * receiver 收件人
 * receiver_tel 收件人電話
 * receiver_email 收件人信箱
 * receiver_gender 收件人性別
 * receiver_birthday 收件人生日
 * receive_address 收件人地址
 * receive_remark 收件人備註
 * package_way 包裝方式
 * status 狀態
 * ~ 成立 established
 * ~ 未成立 not-established
 * ~ 申請部分退訂 return-part-apply
 * ~ 申請全部退訂 return-all-apply
 * ~ 部分退訂完成 return-part-complete
 * ~ 全部退訂完成 return-all-complete
 * ~ 訂單完成 complete
 * status_remark 狀態備註
 * receive_way 收貨方式
 * ship_way 物流方式
 * ship_start_time 運送開始時間
 * ship_end_time 運送結束時間
 * ship_remark 運送備註
 * ship_date 運送日期
 * delivery_date 配送日期
 * ship_status 運送狀態
 * ~ unfulfilled：待出貨
 * ~ collected：準備出貨
 * ~ shipped：已出貨
 * ~ Pending：問題待解決
 * customer_service_remark 客戶服務備註
 * pay_type 付款類型
 * pay_status 付款狀態
 * ~ sumulate-paid: 模擬測試付款
 * ~ paid: 已付款
 * ~ not-paid: 未付款
 * invoice_number 發票號碼
 * invoice_status 發票狀態
 * ~ done: 完成
 * ~ fail: 失敗
 * invoice_type 發票類型
 * ~ 電子發票 personal,電子三聯式發票 company
 * invoice_carrier_type 發票載具類型
 * ~ phone,email,certificate
 * invoice_carrier_number 發票載具編號(載具內容)
 * invoice_tax_type 發票含稅狀態
 * invoice_title 發票抬頭
 * invoice_company_name 發票公司名稱
 * invoice_address 發票地址
 * invoice_email 發票信箱
 * invoice_uniform_number 發票統一編號
 * shop_cart_products 訂單商品
 * ecpay_merchant_id 綠界特店編號
 * ecpay_trade_no 綠界的交易編號
 * ecpay_charge_fee 手續費
 * pay_at 付款時間
 * csv_pay_from CSV_繳費超商
 * csv_payment_no CSV_繳費代碼
 * csv_payment_url CSV_繳費連結
 * barcode_pay_from Barcode_繳費超商
 * atm_acc_bank ATM_付款人銀行代碼
 * atm_acc_no 付款人銀行帳號後五碼
 * card_auth_code 信用卡或銀聯卡_銀行授權碼
 * card_gwsr 信用卡或銀聯卡_授權交易單號
 * card_process_at 信用卡或銀聯卡_交易時間
 * card_amount 信用卡或銀聯卡_金額
 * card_pre_six_no 信用卡或銀聯卡_信用卡卡號前六碼
 * card_last_four_no 信用卡或銀聯卡_信用卡卡號末四碼
 * card_stage 信用卡或銀聯卡_分期期數
 * card_stast 信用卡或銀聯卡_首期金額
 * card_staed 信用卡或銀聯卡_各期金額
 * card_red_dan 信用卡或銀聯卡_紅利扣點
 * card_red_de_amt 信用卡或銀聯卡_紅利折抵金額
 * card_red_ok_amt 信用卡或銀聯卡_實際扣款金額
 * card_red_yet 信用卡或銀聯卡_紅利剩餘點數
 * card_period_type 信用卡或銀聯卡_訂單建立時的所設定的週期種類
 * card_frequency 信用卡或銀聯卡_訂單建立時的所設定的執行頻率
 * card_exec_times 信用卡或銀聯卡_訂單建立時的所設定的執行次數
 * card_period_amount 信用卡或銀聯卡_訂單建立時的每次要授權金額
 * card_total_success_times 信用卡或銀聯卡_目前已成功授權的次數
 * card_total_success_amount 信用卡或銀聯卡_目前已成功授權的金額合計
 * @authenticated
 *
 */
class ShopOrderController extends Controller
{
  public $model                   = 'Wasateam\Laravelapistone\Models\ShopOrder';
  public $name                    = 'shop_order';
  public $resource                = 'Wasateam\Laravelapistone\Resources\ShopOrder';
  public $resource_for_collection = 'Wasateam\Laravelapistone\Resources\ShopOrderCollection';
  public $input_fields            = [
    'no',
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
    'ship_start_time',
    'ship_end_time',
    'ship_remark',
    'ship_date',
    'ship_status',
    'customer_service_remark',
    // 'pay_type',
    // 'pay_status',
    // 'discounts',
    // 'freight',
    // 'products_price',
    // 'order_price',
    // 'invoice_number',
    // 'invoice_status',
    'invoice_type',
    'invoice_carrier_type',
    'invoice_carrier_number',
    'invoice_tax_type',
    'invoice_title',
    'invoice_company_name',
    'invoice_address',
    'invoice_email',
    'invoice_uniform_number',
  ];
  public $search_fields = [
    'no',
    'receiver_tel',
  ];
  public $filter_fields = [
    'type',
    'order_type',
    'ship_status',
    'pay_status',
    'invoice_status',
    'ship_remark',
  ];
  public $belongs_to = [
    'user',
    'user_address',
    'shop_ship_area_setting',
    'shop_ship_time_setting',
    'area',
    'area_section',
  ];
  public $filter_belongs_to = [
    'user',
    'area',
    'area_section',
    'shop_ship_time_setting',
  ];
  public $time_fields = [
    'created_at',
    'updated_at',
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
      $this->input_fields[]  = 'pay_status';
      $this->input_fields[]  = 'discounts';
      $this->input_fields[]  = 'freight';
      $this->input_fields[]  = 'products_price';
      $this->input_fields[]  = 'order_price';
      $this->input_fields[]  = 'delivery_date';
      $this->filter_fields[] = 'id';
    }
  }

  /**
   * Index
   *
   * @queryParam area int 地區 No-Example 1
   * @queryParam area_section int 子地區 No-Example 1
   * @queryParam shop_ship_time_setting int 配送時段 No-Example 1
   * @queryParam ship_remark string  No-example null,not_null
   * @queryParam type string  No-example type
   * @queryParam order_type string  No-example order_type
   * @queryParam ship_status string  No-example ship_status
   * @queryParam pay_status string  No-example pay_status
   * @queryParam invoice_status string  No-example invoice_status
   * @queryParam order_by string  No-example created_at,updated_at
   * @queryParam order_way string  No-example asc,desc
   * @queryParam start_time string  No-example 2020-10-10
   * @queryParam end_time string  No-example 2021-10-11
   * @queryParam time_field string  No-example created_at,updated_at
   * @queryParam user number  No-example 1
   */
  public function index(Request $request, $id = null)
  {
    return ModelHelper::ws_IndexHandler($this, $request, $id);
  }

  /**
   * Auth Shop Order Index
   *
   * @queryParam area int 地區 No-Example 1
   * @queryParam area_section int 子地區 No-Example 1
   * @queryParam shop_ship_time_setting int 配送時段 No-Example 1
   * @queryParam ship_remark string  No-example null,not_null
   * @queryParam type string  No-example type
   * @queryParam order_type string  No-example order_type
   * @queryParam ship_status string  No-example ship_status
   * @queryParam pay_status string  No-example pay_status
   * @queryParam invoice_status string  No-example invoice_status
   * @queryParam order_by string  No-example created_at,updated_at
   * @queryParam order_way string  No-example asc,desc
   * @queryParam start_time string  No-example 2020-10-10
   * @queryParam end_time string  No-example 2021-10-11
   * @queryParam time_field string  No-example created_at,updated_at
   */
  public function auth_shop_order_index(Request $request, $id = null)
  {
    return ModelHelper::ws_IndexHandler($this, $request, $id, $request->get_all, function ($model) {
      $auth = Auth::user();
      return $model->where('user_id', $auth->id);
    });
  }

  /**
   * Store
   *
   * @bodyParam user int 人員 Example:1
   * @bodyParam no string 訂單編號 No-example
   * @bodyParam orderer string 訂購者 Example:orderer_name
   * @bodyParam orderer_tel string 訂購者電話 Example:0900-000-000
   * @bodyParam orderer_birthday string 訂購者生日 Example:1000-10-10
   * @bodyParam orderer_email string 訂購者mail Example:aa@aa.com
   * @bodyParam orderer_gender string 訂購者性別 Example:female,male
   * @bodyParam receiver string 收件者 No-example Example:receiver_name
   * @bodyParam receiver_tel string 收件人電話 Example:0900-000-000
   * @bodyParam receiver_email string 收件人mail Example:aa@aa.com
   * @bodyParam receiver_gender string 收件人性別 Example:female,male
   * @bodyParam receiver_birthday string 收件人生日 Example:1000-10-10
   * @bodyParam user_address int 人員地址 Example:1
   * @bodyParam receive_address string 收件人住址 Example:address
   * @bodyParam receive_remark text 收件備註 Example:receive_remark
   * @bodyParam package_way text 包裝方式 Example:nomarl-package
   * @bodyParam status text 訂單狀態 Example:order_status
   * @bodyParam status_remark text 狀態備註 Example:status_remark
   * @bodyParam receive_way text 收貨方式 Example:receive_way
   * @bodyParam ship_way text 配送方式 Example:ship_way
   * @bodyParam shop_ship_area_setting int 配送地區 Example:1
   * @bodyParam shop_ship_time_setting int 配送時間 Example:1
   * @bodyParam ship_start_time text 出貨開始時間 Example:10:10
   * @bodyParam ship_end_time text 出貨結束時間 Example:10:20
   * @bodyParam ship_remark text 出貨備註 Example:ship_remark
   * @bodyParam ship_date text 出貨日期 Example:2021-10-11
   * @bodyParam delivery_date text 配送日期 Example:2021-10-11
   * @bodyParam ship_status text 出貨狀態 Example:ship_status
   * @bodyParam customer_service_remark text 客服備註 Example:customer_service_remark
   * @bodyParam discounts text  優惠活動 Example:discounts
   * @bodyParam freight text 運費  Example:freight
   * @bodyParam products_price text  商品總金額 Example:products_price
   * @bodyParam order_price text 訂單金額  Example:order_price
   * @bodyParam invoice_type string 發票類型 No-example
   * @bodyParam invoice_carrier_type string 發票載具類型 No-example
   * @bodyParam invoice_carrier_number string 發票載具編號 No-example
   * @bodyParam invoice_tax_type string 發票含稅狀態 No-example
   * @bodyParam invoice_title string 發票抬頭 Example: 山葵組設計股份有限公司
   * @bodyParam invoice_company_name 發票公司名稱 string Example: 山葵組設計股份有限公司
   * @bodyParam invoice_address string 發票地址 No-example
   * @bodyParam invoice_email string 發票信箱 No-example
   * @bodyParam invoice_uniform_number string 發票統一編號 No-example
   * @bodyParam shop_cart_products object 訂單商品 Example:[{"id":1}]
   */

  public function store(Request $request, $id = null)
  {
    if (config('stone.mode') == 'cms') {
      return ModelHelper::ws_StoreHandler($this, $request, $id);
    } else if (config('stone.mode') == 'webapi') {
      if ($request->user != Auth::user()->id) {
        return response()->json([
          'message' => 'not you',
        ], 400);
      }
      if (!$request->has('shop_cart_products') || !is_array($request->shop_cart_products)) {
        return response()->json([
          'message' => 'products required.',
        ], 400);
      }
      if (!$request->has('shop_ship_time_setting')) {
        return response()->json([
          'message' => 'shop_ship_time_setting required.',
        ], 400);
      }
      $shop_ship_time_setting = ShopShipTimeSetting::where('id', $request->shop_ship_time_setting)->first();
      if ($shop_ship_time_setting->max_count <= count($shop_ship_time_setting->today_shop_orders)) {
        return response()->json([
          'message' => 'shop_ship_time_setting is max today.',
        ], 400);
      }
      $my_cart_products  = $request->shop_cart_products;
      $_my_cart_products = [];
      $order_type        = "";
      foreach ($my_cart_products as $my_cart_product) {
        $cart_product = ShopCartProduct::where('id', $my_cart_product['id'])->where('status', 1)->where('count', ">", 0)->first();
        if (!$cart_product) {
          return response()->json([
            'message' => 'no data.',
          ], 400);
        }
        if ($cart_product->user_id != Auth::user()->id) {
          return response()->json([
            'message' => 'not your cart product.',
          ], 400);
        }
        if ($cart_product->count > $cart_product->shop_product->stock_count) {
          return response()->json([
            'message' => 'products not enough;',
          ], 400);
        }
        if ($order_type && $cart_product->shop_product->order_type != $order_type) {
          return response()->json([
            'message' => 'product is not same order type.',
          ], 400);
        }
        $order_type          = $cart_product->shop_product->order_type;
        $_my_cart_products[] = $cart_product;
      }

      # Update User Info
      ShopHelper::updateUserInfoFromShopOrderRequest(Auth::user(), $request);

      # invoice
      $invoice_status = null;
      $invoice_number = null;
      if (config('stone.invoice')) {
        if (config('stone.invoice.service') == 'ecpay') {
          if ($request->has('invoice_type')) {
            try {
              $invoice_type   = $request->invoice_type;
              $customer_email = $request->orderer_email;
              $customer_tel   = $request->orderer_tel;
              $customer_addr  = $request->receive_address;
              $order_amount   = ShopHelper::getOrderAmount($_my_cart_products);
              $items          = EcpayHelper::getInvoiceItemsFromShopCartProducts($_my_cart_products);
              $customer_id    = Auth::user()->id;
              $post_data      = [
                'Items'         => $items,
                'SalesAmount'   => $order_amount,
                'TaxType'       => 1,
                'CustomerEmail' => $customer_email,
                'CustomerAddr'  => $customer_addr,
                'CustomerPhone' => $customer_tel,
                'CustomerID'    => $customer_id,
              ];
              if ($invoice_type == 'personal') {
                $invoice_carrier_type      = $request->invoice_carrier_type;
                $invoice_carrier_number    = $request->invoice_carrier_number;
                $post_data['Print']        = 0;
                $post_data['CustomerName'] = $request->orderer;
                if ($invoice_carrier_type == 'mobile') {
                  $post_data['CarrierType'] = 3;
                  $post_data['CarrierNum']  = $invoice_carrier_number;
                } else if ($invoice_carrier_type == 'certificate') {
                  $post_data['CarrierType'] = 2;
                  $post_data['CarrierNum']  = $invoice_carrier_number;
                } else if ($invoice_carrier_type == 'email') {
                  $post_data['CarrierType']   = 1;
                  $post_data['CarrierNum']    = '';
                  $post_data['CustomerEmail'] = $invoice_carrier_number;
                }
              } else if ($invoice_type == 'triple') {
                $invoice_title                   = $request->invoice_title;
                $invoice_uniform_number          = $request->invoice_uniform_number;
                $post_data['CarrierType']        = '';
                $post_data['Print']              = 1;
                $post_data['CustomerName']       = $invoice_title;
                $post_data['CustomerIdentifier'] = $invoice_uniform_number;
              }
              $post_data      = EcpayHelper::getInvoicePostData($post_data);
              $invoice_number = EcpayHelper::createInvoice($post_data);
              $invoice_status = 'done';
            } catch (\Throwable $th) {
              $invoice_status = 'fail';
            }
          }
        }
      }

      # Shop Order Shop Product
      return ModelHelper::ws_StoreHandler($this, $request, $id, function ($model) use ($my_cart_products, $invoice_status, $invoice_number, $order_type) {
        foreach ($my_cart_products as $my_cart_product) {
          $new_order_product                       = new ShopOrderShopProduct;
          $cart_product                            = ShopCartProduct::where('id', $my_cart_product['id'])->where('status', 1)->first();
          $shop_product                            = $cart_product->shop_product;
          $new_order_product->name                 = $shop_product->name;
          $new_order_product->subtitle             = $shop_product->subtitle;
          $new_order_product->count                = $cart_product->count;
          $new_order_product->original_count       = $cart_product->count;
          $new_order_product->price                = $shop_product->price;
          $new_order_product->discount_price       = $shop_product->discount_price;
          $new_order_product->spec                 = $shop_product->spec;
          $new_order_product->weight_capacity      = $shop_product->weight_capacity;
          $new_order_product->cover_image          = $shop_product->cover_image;
          $new_order_product->order_type           = $shop_product->order_type;
          $new_order_product->freight              = $shop_product->freight;
          $new_order_product->shop_product_id      = $shop_product->id;
          $new_order_product->cost                 = $shop_product->cost;
          $new_order_product->shop_cart_product_id = $my_cart_product['id'];
          $new_order_product->shop_order_id        = $model->id;
          $new_order_product->save();
          $cart_product->status = 0;
          $cart_product->save();
          ShopHelper::shopOrderProductChangeCount($new_order_product->id);
        }
        ShopHelper::changeShopOrderPrice($model->id, $order_type);
        $model->status = 'not-established';
        $model->save();

        # Order Type
        if ($order_type) {
          $model->order_type = $order_type;
          $model->save();
        }
        # Invoice
        if ($invoice_status) {
          $model->invoice_status = $invoice_status;
          if ($invoice_status == 'done') {
            $model->invoice_number = $invoice_number;
          }
          $model->save();
        }
      });
    }
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
   * @bodyParam no string 訂單編號 No-example
   * @bodyParam orderer string 訂購者 Example:orderer_name
   * @bodyParam orderer_tel string 訂購者電話 Example:0900-000-000
   * @bodyParam orderer_birthday string 訂購者生日 Example:1000-10-10
   * @bodyParam orderer_email string 訂購者mail Example:aa@aa.com
   * @bodyParam orderer_gender string 訂購者性別 Example:female,male
   * @bodyParam receiver string 收件者 No-example Example:receiver_name
   * @bodyParam receiver_tel string 收件人電話 Example:0900-000-000
   * @bodyParam receiver_email string 收件人mail Example:aa@aa.com
   * @bodyParam receiver_gender string 收件人性別 Example:female,male
   * @bodyParam receiver_birthday string 收件人生日 Example:1000-10-10
   * @bodyParam user_address int 人員地址 Example:1
   * @bodyParam receive_address string 收件人住址 Example:address
   * @bodyParam receive_remark text 收件備註 Example:receive_remark
   * @bodyParam package_way text 包裝方式 Example:nomarl-package
   * @bodyParam status text 訂單狀態 Example:order_status
   * @bodyParam status_remark text 狀態備註 Example:status_remark
   * @bodyParam receive_way text 收貨方式 Example:receive_way
   * @bodyParam ship_way text 配送方式 Example:ship_way
   * @bodyParam shop_ship_area_setting int 配送地區 Example:1
   * @bodyParam shop_ship_time_setting int 配送時間 Example:1
   * @bodyParam ship_start_time text 出貨開始時間 Example:10:10
   * @bodyParam ship_end_time text 出貨結束時間 Example:10:20
   * @bodyParam ship_remark text 出貨備註 Example:ship_remark
   * @bodyParam ship_date text 出貨日期 Example:2021-10-11
   * @bodyParam delivery_date text 配送日期 Example:2021-10-11
   * @bodyParam ship_status text 出貨狀態 Example:ship_status
   * @bodyParam customer_service_remark text 客服備註 Example:customer_service_remark
   * @bodyParam discounts text  優惠活動 Example:discounts
   * @bodyParam freight text 運費  Example:freight
   * @bodyParam products_price text  商品總金額 Example:products_price
   * @bodyParam order_price text 訂單金額  Example:order_price
   * @bodyParam invoice_type string 發票類型 No-example
   * @bodyParam invoice_carrier_type string 發票載具類型 No-example
   * @bodyParam invoice_carrier_number string 發票載具編號 No-example
   * @bodyParam invoice_tax_type string 發票含稅狀態 No-example
   * @bodyParam invoice_title string 發票抬頭 Example: 山葵組設計股份有限公司
   * @bodyParam invoice_company_name 發票公司名稱 string Example: 山葵組設計股份有限公司
   * @bodyParam invoice_address string 發票地址 No-example
   * @bodyParam invoice_email string 發票信箱 No-example
   * @bodyParam invoice_uniform_number string 發票統一編號 No-example
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
   * Export Pdf Signedurl
   *
   * @queryParam shop_orders  訂單ids No-example 1,2,3
   */
  public function export_pdf_signedurl(Request $request)
  {
    $shop_orders = $request->has('shop_orders') ? $request->shop_orders : null;
    return URL::temporarySignedRoute(
      'shop_order_export_pdf',
      now()->addMinutes(30),
      ['shop_orders' => $shop_orders]
    );
  }

  /**
   * Export Pdf
   *
   */
  public function export_pdf(Request $request)
  {
    $_shop_orders = $request->has('shop_orders') ? $request->shop_orders : null;
    if (!$_shop_orders) {
      return response()->json([
        'message' => 'required shop_orders;',
      ], 400);
    }
    $shop_order_ids = array_map('intval', explode(',', $_shop_orders));
    $datas          = [];
    foreach ($shop_order_ids as $shop_order_id) {
      //order data
      $shop_order = ShopOrder::find($shop_order_id);
      if (!$shop_order) {
        return response()->json([
          'message' => 'no shop_order;',
        ], 400);
      }
      $delivery_time = Carbon::parse($shop_order->delivery_date)->format('Y-m-d') . '/' . $shop_order->ship_start_time . '-' . $shop_order->ship_end_time;
      $orderer       = $shop_order->orderer . '/' . $shop_order->orderer_tel;
      $order_data    = [
        'id'              => $shop_order->id,
        'delivery_time'   => $delivery_time,
        'no'              => $shop_order->no,
        'orderer'         => $orderer,
        'activity'        => '',
        'receiver'        => $shop_order->receiver,
        'order_date'      => Carbon::parse($shop_order->created_at)->format('Y-m-d'),
        'receiver_tel'    => $shop_order->receiver_tel,
        'receive_address' => $shop_order->receive_address,
        'receive_way'     => $shop_order->receive_way,
        'receive_remark'  => $shop_order->receive_remark,
      ];

      //products in shop order
      $shop_order_shop_products = [];
      if ($shop_order->shop_order_shop_products) {
        $shop_order_shop_products = ShopHelper::fetchShopOrderProduct($shop_order->shop_order_shop_products);
      }
      $order_data['shop_order_shop_products'] = $shop_order_shop_products;

      $datas[] = $order_data;
      //pdf
    }
    $pdf = PDF::loadView('wasa.pdf.shop_order_picking_export', [
      'export_time' => Carbon::now()->format('Y.m.d H:i:s'),
      'shop_orders' => $datas,
    ]);
    return $pdf->stream('揀貨單.pdf');
  }

  /**
   * Export Excel Signedurl
   *
   * @queryParam shop_orders  訂單ids No-example 1,2,3
   * @queryParam get_all  訂單ids No-example 0 or 1
   * @queryParam country_code code 國家  No-example : tw
   */
  public function export_excel_signedurl(Request $request)
  {
    $shop_orders  = $request->has('shop_orders') ? $request->shop_orders : null;
    $get_all      = $request->has('get_all') ? $request->get_all : 0;
    $country_code = $request->has('country_code') ? $request->country_code : null;
    return URL::temporarySignedRoute(
      'shop_order_export_excel',
      now()->addMinutes(30),
      ['shop_orders' => $shop_orders, 'get_all' => $get_all, 'country_code' => $country_code]
    );
  }

  /**
   * Export Excel
   *
   */
  public function export_excel(Request $request)
  {
    $shop_orders  = $request->has('shop_orders') ? $request->shop_orders : null;
    $get_all      = $request->has('get_all') ? $request->get_all : 0;
    $country_code = $request->has('country_code') ? $request->country_code : null;
    return Excel::download(new ShopOrderExport($shop_orders, $get_all, $country_code), 'shop_orders.xlsx');
  }
}
