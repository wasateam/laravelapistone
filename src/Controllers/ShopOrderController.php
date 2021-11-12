<?php

namespace Wasateam\Laravelapistone\Controllers;

use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Http\Request;
use Wasateam\Laravelapistone\Helpers\ModelHelper;
use Wasateam\Laravelapistone\Helpers\ShopHelper;
use Wasateam\Laravelapistone\Models\ShopCartProduct;
use Wasateam\Laravelapistone\Models\ShopOrderShopProduct;

/**
 * @group 訂單
 *
 * @authenticated
 *
 * 訂單 API
 */
class ShopOrderController extends Controller
{
  public $model                   = 'Wasateam\Laravelapistone\Models\ShopOrder';
  public $name                    = 'shop_order';
  public $resource                = 'Wasateam\Laravelapistone\Resources\ShopOrder';
  public $resource_for_collection = 'Wasateam\Laravelapistone\Resources\ShopOrderCollection';
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
    // 'receipt_number',
    // 'receipt_status',
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
   * Auth Shop Order Index
   *
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
   * @bodyParam type string 訂單類型 Example:type
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
   * @bodyParam receive_address string 收件人住址 Example:address
   * @bodyParam receive_remark text 收件備註 Example:receive_remark
   * @bodyParam package_way text 包裝方式 Example:nomarl-package
   * @bodyParam status text 訂單狀態 Example:order_status
   * @bodyParam status_remark text 狀態備註 Example:status_remark
   * @bodyParam receive_way text 收貨方式 Example:receive_way
   * @bodyParam ship_way text 配送方式 Example:ship_way
   * @bodyParam delivery_date text 配送日期 Example:2021-10-10
   * @bodyParam ship_start_time text 出貨開始時間 Example:10:10
   * @bodyParam ship_end_time text 出貨結束時間 Example:10:20
   * @bodyParam ship_remark text 出貨備註 Example:ship_remark
   * @bodyParam ship_date text 出貨日期 Example:2021-10-11 21:00:00
   * @bodyParam ship_status text 出貨狀態 Example:ship_status
   * @bodyParam customer_service_remark text 客服備註 Example:customer_service_remark
   * @bodyParam pay_type text  付款方式 Example:pay_type
   * @bodyParam pay_status text  付款狀態 Example:pay_status
   * @bodyParam discounts text  優惠活動 Example:discounts
   * @bodyParam freight text 運費  Example:freight
   * @bodyParam products_price text  商品總金額 Example:products_price
   * @bodyParam order_price text 訂單金額  Example:order_price
   * @bodyParam receipt_number text  發票號碼 Example:receipt_number
   * @bodyParam receipt_status text  發票狀態 Example:receipt_status
   * @bodyParam receipt_type text  發票形式 Example:receipt_type
   * @bodyParam receipt_carrier_number text 發票載具編號  Example:receipt_carrier_number
   * @bodyParam receipt_tax text  統一編號  Example:receipt_tax
   * @bodyParam receipt_title text  抬頭 Example:receipt_title
   * @bodyParam shop_cart_products object 訂單商品 Example:[
   * {"id":1}]
   *
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
      $my_cart_products = $request->shop_cart_products;
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
      }
      return ModelHelper::ws_StoreHandler($this, $request, $id, function ($model) use ($my_cart_products) {
        foreach ($my_cart_products as $my_cart_product) {
          $new_order_product                       = new ShopOrderShopProduct;
          $cart_product                            = ShopCartProduct::where('id', $my_cart_product['id'])->where('status', 1)->first();
          $shop_product                            = $cart_product->shop_product;
          $new_order_product->name                 = $shop_product->name;
          $new_order_product->subtitle             = $shop_product->subtitle;
          $new_order_product->count                = $cart_product->count;
          $new_order_product->price                = $shop_product->price;
          $new_order_product->discount_price       = $shop_product->discount_price;
          $new_order_product->spec                 = $shop_product->spec;
          $new_order_product->weight_capacity      = $shop_product->weight_capacity;
          $new_order_product->cover_image          = $shop_product->cover_image;
          $new_order_product->shop_product_id      = $shop_product->id;
          $new_order_product->shop_cart_product_id = $my_cart_product['id'];
          $new_order_product->shop_order_id        = $model->id;
          $new_order_product->save();
          $cart_product->status = 0;
          $cart_product->save();
          ShopHelper::shopOrderProductChangeCount($new_order_product->id);
        }
        ShopHelper::changeShopOrderPrice($model->id);
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
   * @bodyParam type string 訂單類型 No-example
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
   * @bodyParam receive_address string 收件人住址 Example:address
   * @bodyParam receive_remark text 收件備註 Example:receive_remark
   * @bodyParam package_way text 包裝方式 Example:nomarl-package
   * @bodyParam status text 訂單狀態 Example:order_status
   * @bodyParam status_remark text 狀態備註 Example:status_remark
   * @bodyParam receive_way text 收貨方式 Example:receive_way
   * @bodyParam ship_way text 配送方式 Example:ship_way
   * @bodyParam delivery_date text 配送日期 Example:2021-10-10
   * @bodyParam ship_start_time text 出貨開始時間 Example:10:10
   * @bodyParam ship_end_time text 出貨結束時間 Example:10:20
   * @bodyParam ship_remark text 出貨備註 Example:ship_remark
   * @bodyParam ship_date text 出貨日期 Example:2021-10-11 21:00:00
   * @bodyParam ship_status text 出貨狀態 Example:ship_status
   * @bodyParam customer_service_remark text 客服備註 Example:customer_service_remark
   * @bodyParam pay_type text  付款方式 Example:pay_type
   * @bodyParam pay_status text  付款狀態 Example:pay_status
   * @bodyParam discounts text  優惠活動 Example:discounts
   * @bodyParam freight text 運費  Example:freight
   * @bodyParam products_price text  商品總金額 Example:products_price
   * @bodyParam order_price text 訂單金額  Example:order_price
   * @bodyParam receipt_number text  發票號碼 Example:receipt_number
   * @bodyParam receipt_status text  發票狀態 Example:receipt_status
   * @bodyParam receipt_type text  發票形式 Example:receipt_type
   * @bodyParam receipt_carrier_number text 發票載具編號  Example:receipt_carrier_number
   * @bodyParam receipt_tax text  統一編號  Example:receipt_tax
   * @bodyParam receipt_title text  抬頭 Example:receipt_title
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
