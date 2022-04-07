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
use Wasateam\Laravelapistone\Helpers\ModelHelper;
use Wasateam\Laravelapistone\Helpers\ShopHelper;
use Wasateam\Laravelapistone\Helpers\StrHelper;
use Wasateam\Laravelapistone\Models\ShopOrder;

/**
 * @group ShopOrder 訂單
 *
 * type 類型
 * order_type 訂單類型
 * ~ pre-order 預購
 * ~ next-day 隔日配
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
 * ~ 取消 cancel
 * ~ 取消完成 cancel-complete
 * ~ 訂單完成 complete
 * status_remark 狀態備註
 * receive_way 收貨方式
 * ~ 電話聯絡收件人 phone-contact
 * ~ 電聯收件人後，交由管理室代收 phone-contact-building-manager
 * ~ 不需電聯，直接交由管理室代收 building-manager
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
 * ~ Credit: 信用卡
 * ~ CVS: CVS
 * ~ ATM: ATM
 * ~ BARCODE: 超商條碼
 * ~ line_pay: LINE Pay
 * pay_status 付款狀態
 * ~ sumulate-paid: 模擬測試付款
 * ~ waiting: 待付款
 * ~ paid: 已付款
 * ~ not-paid: 沒付款
 * ~ returned: 已退款
 * invoice_number 發票號碼
 * reinvoice_at 發票重新開立時間
 * invoice_status 發票狀態
 * ~ null: 未開立
 * ~ waiting: 待開立
 * ~ done: 完成
 * ~ fail: 失敗
 * ~ no-need: 無需開發票
 * invoice_type 發票類型
 * ~ 電子發票 personal,電子三聯式發票 triple
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
 * bonus_points 訂單想使用(扣除)的紅利點數
 * bonus_points_deduct 紅利點數折抵
 * discount_code 折扣碼
 * campaign_deduct 活動折抵
 * freight 運費
 * products_price 商品價格總計
 * order_price 訂單費用總計
 * user 訂購會員
 * invite_no 邀請碼
 * invite_no_deduct 邀請碼折抵
 * need_handle 有問題待處理
 * remark_status 備註狀態
 * ~ waiting-handle 問題待處理
 * ~ customer-service-remarked 有客服備註
 * return_at 退貨時間
 * return_price 退貨金額
 * return_reason 退貨原由
 * return_remark 退貨備註
 * created_at 訂單建立時間
 * source 來源
 * ~ web-pc
 * ~ web-mobile
 * ~ app
 *
 * api-
 * ReCreate 用於一筆訂單付款失敗，而要重新建立一筆新的訂單，會帶入前一筆訂單資料，但no,uuid需重新建立
 *
 * Update
 * shop_return_records 內帶陣列資料
 *
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
    // 'no',
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
    // 'status',
    'status_remark',
    'receive_way',
    'ship_way',
    // 'ship_start_time',
    // 'ship_end_time',
    'ship_remark',
    // 'ship_date',
    // 'ship_status',
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
    'invite_no',
    'source',
  ];
  public $search_fields = [
    'no',
    'receiver_tel',
    'orderer',
  ];
  public $filter_fields = [
    'type',
    'order_type',
    'ship_status',
    'pay_status',
    'invoice_status',
    'ship_remark',
    'status',
  ];
  public $belongs_to = [
    // 'user',
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
  public $filter_time_fields = [
    'ship_date',
    'return_at',
    'created_at',
  ];
  public $time_fields = [
    'created_at',
    'updated_at',
  ];
  public $order_fields = [
    'updated_at',
    'created_at',
    'receive_address',
  ];
  public $child_models = [
    'shop_return_records' => '\Wasateam\Laravelapistone\Controllers\ShopReturnRecordController',
  ];
  public $uuid = false;

  public $paginate = 15;

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
      $this->input_fields[]  = 'need_handle';
      $this->input_fields[]  = 'return_price';
      $this->input_fields[]  = 'return_reason';
      $this->input_fields[]  = 'return_remark';
      $this->input_fields[]  = 'status';
      $this->filter_fields[] = 'id';
      $this->filter_fields[] = 'reinvoice_at';
      $this->filter_fields[] = 'status';
      $this->filter_fields[] = 'need_handle';
      $this->filter_fields[] = 'ship_status';
    }
    if (config('stone.shop')) {
      if (config('stone.shop.order')) {
        if (config('stone.shop.order.per_page')) {
          $this->paginate = config('stone.shop.order.per_page');
        }
      }
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
   * @queryParam remark_status 備註狀態 string  No-example 1
   */
  public function index(Request $request, $id = null)
  {
    return ModelHelper::ws_IndexHandler($this, $request, $id, false, function ($snap) use ($request) {
      if ($request->filled('remark_status')) {
        if ($request->remark_status == 'waiting-handle') {
          $snap = $snap->where('need_handle', 1);
        } else if ($request->remark_status == 'customer-service-remarked') {
          $snap = $snap->whereNotNull('customer_service_remark');
        }
      }
      return $snap;
    });
  }

  // /**
  //  * Auth Shop Order Index
  //  *
  //  * @queryParam area int 地區 No-Example 1
  //  * @queryParam area_section int 子地區 No-Example 1
  //  * @queryParam shop_ship_time_setting int 配送時段 No-Example 1
  //  * @queryParam ship_remark string  No-example null,not_null
  //  * @queryParam type string  No-example type
  //  * @queryParam order_type string  No-example order_type
  //  * @queryParam ship_status string  No-example ship_status
  //  * @queryParam pay_status string  No-example pay_status
  //  * @queryParam invoice_status string  No-example invoice_status
  //  * @queryParam order_by string  No-example created_at,updated_at
  //  * @queryParam order_way string  No-example asc,desc
  //  * @queryParam start_time string  No-example 2020-10-10
  //  * @queryParam end_time string  No-example 2021-10-11
  //  * @queryParam time_field string  No-example created_at,updated_at
  //  */
  // public function auth_shop_order_index(Request $request, $id = null)
  // {
  //   return ModelHelper::ws_IndexHandler($this, $request, $id, $request->get_all, function ($model) {
  //     $auth = Auth::user();
  //     return $model->where('user_id', $auth->id);
  //   });
  // }

  /**
   * Store
   *
   * @bodyParam user int 人員 Example:1
   * @bodyParam no string 訂單編號 Example:AC12342
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
   * @bodyParam bonus_points int 紅利點數 Example:30
   * @bodyParam discount_code string 折扣碼 Example:SEXYAPPLE
   * @bodyParam invite_no string 邀請碼 Example:SEXYORANGE
   * @bodyParam source string 來源 Example:web-pc,web-mobile,app
   */

  public function store(Request $request, $id = null)
  {
    if (config('stone.mode') == 'cms') {
      return ModelHelper::ws_StoreHandler($this, $request, $id);
    } else if (config('stone.mode') == 'webapi') {

      if (!$request->has('shop_cart_products') || !is_array($request->shop_cart_products)) {
        throw new \Wasateam\Laravelapistone\Exceptions\FieldRequiredException('shop_cart_products');
      }

      $user = Auth::user();

      ShopHelper::shopShipTimeLimitCheck($request);

      $order_type = ShopHelper::getOrderTypeFromShopCartProducts($request->shop_cart_products);

      $filtered_cart_products = ShopHelper::filterCartProducts($request->shop_cart_products, $user, $order_type);

      ShopHelper::checkBonusPointEnough($request);

      ShopHelper::checkShopOrderInviteNo($request, $user);

      ShopHelper::updateUserInfoFromShopOrderRequest(Auth::user(), $request);

      $discount_code = $request->has('discount_code') ? $request->discount_code : null;
      $invite_no     = $request->has('invite_no') ? $request->invite_no : null;
      $bonus_points  = $request->has('bonus_points') ? $request->bonus_points : null;

      return ModelHelper::ws_StoreHandler(
        $this,
        $request,
        $id,
        function ($model) use (
          $filtered_cart_products,
          $discount_code,
          $invite_no,
          $bonus_points
        ) {

          // @Q@
          ShopHelper::deductBonusPointFromShopOrder($model);
          ShopHelper::createShopOrderShopProductsFromCartProducts($filtered_cart_products, $model);
          ShopHelper::setCampaignDeduct($model, $discount_code);
          ShopHelper::updateShopOrderPrice($model, $discount_code, $bonus_points, $invite_no);
          ShopHelper::ShopOrderNoPriceCheck($model);
          ShopHelper::ShopOrderShipTimeSet($model);

        }, function ($model) use (
          $user,
          $order_type
        ) {
          $model->user_id    = $user->id;
          $model->status     = 'not-established';
          $model->pay_status = 'waiting';
          $model->order_type = $order_type;
          return $model;
        });
    }
  }

  /**
   * Calc 計算購物車資訊
   *
   * @bodyParam user int 人員 Example:1
   * @bodyParam shop_cart_products object 訂單商品 Example:[{"id":1}]
   * @bodyParam bonus_points int 紅利點數 Example:30
   * @bodyParam discount_code string 折扣碼 Example:SEXYAPPLE
   * @bodyParam invite_no string 邀請碼 Example:SEXYORANGE
   */
  public function calc(Request $request, $id = null)
  {
    $user = Auth::user();

    ShopHelper::shopShipTimeLimitCheck($request);

    $order_type = ShopHelper::getOrderTypeFromShopCartProducts($request->shop_cart_products);

    $filtered_cart_products = ShopHelper::filterCartProducts($request->shop_cart_products, $user, $order_type);

    ShopHelper::checkBonusPointEnough($request);

    $discount_code = $request->has('discount_code') ? $request->discount_code : null;
    $invite_no     = $request->has('invite_no') ? $request->invite_no : null;
    $bonus_points  = $request->has('bonus_points') ? $request->bonus_points : null;

    $products_price      = ShopHelper::getOrderProductsAmount($filtered_cart_products);
    $campaign_deduct     = ShopHelper::getCampaignDeduct($user, Carbon::now(), $products_price, $discount_code);
    $invite_no_deduct    = ShopHelper::getInviteNoDeduct($products_price, $invite_no, $user);
    $bonus_points_deduct = ShopHelper::getBonusPointsDeduct($bonus_points, $products_price, $campaign_deduct, $invite_no_deduct);
    $freight             = ShopHelper::getFreight($order_type, $products_price, $campaign_deduct, $invite_no_deduct);
    $order_price         = ShopHelper::getOrderPrice($products_price, $freight, $bonus_points_deduct, $campaign_deduct, $invite_no_deduct);

    return response()->json([
      'products_price'      => $products_price,
      'freight'             => $freight,
      'campaign_deduct'     => $campaign_deduct,
      'invite_no_deduct'    => $invite_no_deduct,
      'bonus_points_deduct' => $bonus_points_deduct,
      'order_price'         => $order_price,
    ], 200);
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
   * @bodyParam no string 訂單編號 Example:AC12342
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
   * @bodyParam need_handle boolean 需要處理  Example:1
   * @bodyParam source string 來源 Example:web-pc,web-mobile,app
   *
   */
  public function update(Request $request, $id)
  {
    $ori_model = $this->model::find($id);
    ShopHelper::shopOrderShipStatusModifyCheck($ori_model, $request);
    return ModelHelper::ws_UpdateHandler($this, $request, $id, [], function ($model) use ($ori_model) {
      ShopHelper::createInvoice($model, $ori_model);
      // if(count($model->shop_return_records)==0 &&$ori_model->ship_status=='collected'&&$model)
      // ShopHelper::changeShopOrderPrice($model->id);
      // ShopHelper::updateShopOrderPrice($model);
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
    $_shop_order_ids_str = $request->has('shop_orders') ? $request->shop_orders : null;
    if (!$_shop_order_ids_str) {
      return response()->json([
        'message' => 'required shop_orders;',
      ], 400);
    }
    $shop_order_ids = array_map('intval', explode(',', $_shop_order_ids_str));
    $shop_orders    = ShopOrder::whereIn('id', $shop_order_ids)
      ->whereNull('deleted_at')
      ->orderBy('area_id', 'asc')
      ->orderBy('area_section_id', 'asc')
      ->orderBy('receive_address', 'asc')
      ->get();
    $datas = [];
    foreach ($shop_orders as $shop_order) {
      $delivery_time       = Carbon::parse($shop_order->delivery_date)->format('Y-m-d') . '/' . $shop_order->ship_start_time . '-' . $shop_order->ship_end_time;
      $orderer             = $shop_order->orderer . '/' . $shop_order->orderer_tel;
      $orderer_encode      = StrHelper::encodeString($shop_order->orderer, 'name') . '/' . StrHelper::encodeString($shop_order->orderer_tel, 'tel');
      $receiver            = $shop_order->receiver;
      $receiver_tel        = $shop_order->receiver_tel;
      $receiver_encode     = StrHelper::encodeString($shop_order->receiver, 'name');
      $receiver_tel_encode = StrHelper::encodeString($shop_order->receiver_tel, 'tel');
      $order_data          = [
        'id'                  => $shop_order->id,
        'delivery_time'       => $delivery_time,
        'no'                  => $shop_order->no,
        'orderer'             => $orderer,
        'orderer_encode'      => $orderer_encode,
        'activity'            => '',
        'receiver'            => $receiver,
        'receiver_encode'     => $receiver_encode,
        'order_date'          => Carbon::parse($shop_order->created_at)->format('Y-m-d'),
        'receiver_tel'        => $receiver_tel,
        'receiver_tel_encode' => $receiver_tel_encode,
        'receive_address'     => $shop_order->receive_address,
        'receive_way'         => ShopHelper::getReceiveWayText($shop_order->receive_way),
        'receive_remark'      => $shop_order->receive_remark,
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
    $pdf = PDF::loadView('wasateam::wasa.pdf.shop_order_picking_export', [
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

  /**
   * ReCreate
   *
   * @bodyParam no string 訂單編號 No-example
   */
  public function re_create(Request $request, $id)
  {
    //FIXME ಠ_ಠ
    $shop_order = ShopOrder::where('id', $id)->first();
    if (!$shop_order) {
      throw new \Wasateam\Laravelapistone\Exceptions\FindNoDataException('shop_order');
    }
    if (!$request->no || !isset($request->no)) {
      throw new \Wasateam\Laravelapistone\Exceptions\FieldRequiredException('no');
    }
    //new shop order
    $new_shop_order       = $shop_order->replicate();
    $new_shop_order->no   = $request->no;
    $new_shop_order->uuid = null;
    $new_shop_order->save();

    //new shop order shop product
    $shop_order_shop_products = $shop_order->shop_order_shop_products;
    foreach ($shop_order_shop_products as $shop_order_shop_product) {
      $new_product                = $shop_order_shop_product->replicate();
      $new_product->shop_order_id = $new_shop_order->id;
      $new_product->save();
    }

    $shop_order->repay_shop_order_id = $new_shop_order->id;
    $shop_order->save();
  }

  /**
   * Cancel 取消訂單
   *
   */
  public function cancel(Request $request, $id)
  {
    $shop_order = ShopOrder::find($id);
    if (!$shop_order) {
      throw new \Wasateam\Laravelapistone\Exceptions\FindNoDataException('shop_order');
    }
    if ($request->filled('return_reason')) {
      $shop_order->return_reason = $request->return_reason;
    }
    if ($request->filled('return_remark')) {
      $shop_order->return_remark = $request->return_remark;
    }
    if ($request->filled('return_price')) {
      $shop_order->return_price = $request->return_price;
    }
    $shop_order->status    = 'cancel';
    $shop_order->return_at = Carbon::now();
    $shop_order->save();
    return response()->json([
      'message' => "shop_order canceled.",
      'data'    => $shop_order,
    ], 200);
  }

  /**
   * Return Cancel 取消退訂
   *
   */
  public function return_cancel(Request $request, $id)
  {
    $shop_order = ShopOrder::find($id);
    if (!$shop_order) {
      throw new \Wasateam\Laravelapistone\Exceptions\FindNoDataException('shop_order');
    }
    foreach ($shop_order->shop_return_records as $shop_return_record) {
      $shop_return_record->delete();
    }
    $shop_order->return_reason = null;
    $shop_order->return_remark = null;
    $shop_order->return_price  = null;
    $shop_order->status        = null;
    $shop_order->return_at     = null;
    $shop_order->save();
    return response()->json([
      'message' => "shop_order return canceled.",
      'data'    => $shop_order,
    ], 200);
  }

  /**
   * Batch Update 批次更新
   *
   */
  public function batch_update(Request $request)
  {
    $ids = $request->ids;
    foreach ($ids as $id) {
      $ori_model = $this->model::find($id);
      ShopHelper::shopOrderShipStatusModifyCheck($ori_model, $request);
      ModelHelper::ws_UpdateHandler($this, $request, $id, [], function ($model) use ($ori_model) {
        ShopHelper::createInvoice($model, $ori_model);
      });
    }
    return $this->resource_for_collection::collection($this->model::find($ids));
  }

  /**
   * Create Invoice 建立發票
   *
   */
  public function create_invice($id)
  {
    $shop_order = $this->model::find($id);
    if (!$shop_order) {
      throw new \Wasateam\Laravelapistone\Exceptions\FindNoDataException('shop_order');
    }
    if ($shop_order->pay_status == 'paid' && $shop_order->status == 'established') {
      ShopHelper::createInvoice($shop_order);
    }
  }

  /**
   * ReCreate Invoice 重新建立發票
   *
   */
  public function re_create_invice($id)
  {
    $shop_order = $this->model::find($id);
    if (!$shop_order) {
      throw new \Wasateam\Laravelapistone\Exceptions\FindNoDataException('shop_order');
    }
    if (
      $shop_order->status == 'return-part-complete' ||
      $shop_order->status == 'cancel-complete' ||
      $shop_order->ship_status == 'shipped'
    ) {
      ShopHelper::createInvoice($shop_order);
      $shop_order = $this->model::find($id);
      return $shop_order;
    }
  }
}
