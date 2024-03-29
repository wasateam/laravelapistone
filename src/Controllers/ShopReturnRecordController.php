<?php

namespace Wasateam\Laravelapistone\Controllers;

use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Http\Request;
use Wasateam\Laravelapistone\Helpers\ModelHelper;
use Wasateam\Laravelapistone\Helpers\ShopHelper;
use Wasateam\Laravelapistone\Models\ShopOrder;
use Wasateam\Laravelapistone\Models\ShopOrderShopProduct;
use Wasateam\Laravelapistone\Models\ShopReturnRecord;

/**
 * @group ShopReturnRecord 商品退貨紀錄
 *
 * 商品退貨紀錄API
 *
 * index,store,update,delete,show 欄位
 * count 退貨數量
 * remark 備註
 * return_reason 退貨理由
 * user 誰的訂單
 * shop_order 訂單
 * shop_order_shop_product 訂單商品
 * type 類別
 * ~ return-all 全部退訂
 * ~ return-part 部分退訂
 * status 退訂狀態
 * shop_product 商品(原始商品)
 *
 * return all 欄位
 * shop_order 需退訂訂單id
 * remark 備註
 * return_reason 退貨理由
 *
 * @authenticated
 */
class ShopReturnRecordController extends Controller
{
  public $model                   = 'Wasateam\Laravelapistone\Models\ShopReturnRecord';
  public $name                    = 'shop_return_record';
  public $resource                = 'Wasateam\Laravelapistone\Resources\ShopReturnRecord';
  public $resource_for_collection = 'Wasateam\Laravelapistone\Resources\ShopReturnRecordCollection';
  public $input_fields            = [
    'count',
    'remark',
    'return_reason',
    'status',
    'type',
  ];
  public $belongs_to = [
    'user',
    'shop_order',
    'shop_order_shop_product',
    'shop_product',
  ];
  public $filter_belongs_to = [
    'shop_order',
  ];
  public $order_fields = [
    'updated_at',
    'created_at',
    'count',
  ];

  public function __construct()
  {
    if (config('stone.mode') == 'cms') {
      $this->filter_belongs_to[] = 'user';
      $this->filter_belongs_to[] = 'shop_order_shop_product';
      $this->filter_belongs_to[] = 'shop_product';
    }
  }
  /**
   * Index
   * @queryParam user ids 人員  No-example
   * @queryParam shop_order id 訂單  No-example
   * @queryParam shop_order_shop_product ids 訂單商品  No-example
   * @queryParam shop_product ids 商品  No-example
   *
   */
  public function index(Request $request, $id = null)
  {
    if (config('stone.mode') == 'cms') {
      return ModelHelper::ws_IndexHandler($this, $request, $id);
    } else if (config('stone.mode') == 'webapi') {
      $auth           = Auth::user();
      $shop_order_ids = array_map('intval', explode(',', $request->shop_order));
      foreach ($shop_order_ids as $shop_order_id) {
        $shop_order = ShopOrder::where('id', $shop_order_id)->first();
        if ($shop_order->user->id != $auth->id) {
          return response()->json([
            'message' => 'invaid scopes',
          ], 400);
        }
      }
      return ModelHelper::ws_IndexHandler($this, $request, $id);
    }
  }

  /**
   * Store
   *
   * @bodyParam user int 人員 Example:1
   * @bodyParam shop_order int 訂單 Example:1
   * @bodyParam shop_order_shop_product int 訂單商品 Example:1
   * @bodyParam shop_product int 商品 Example:1
   * @bodyParam count int 數量 Example:1
   * @bodyParam remark string 備註 Example:remark
   * @bodyParam return_reason string 退貨理由 Example:reason
   */
  public function store(Request $request, $id = null)
  {
    $shop_order_shop_product = ShopOrderShopProduct::where('id', $request->shop_order_shop_product)->first();
    $request->request->add(['shop_product' => $shop_order_shop_product->shop_product->id]);
    $request->request->add(['type' => 'return-part']);
    return ModelHelper::ws_StoreHandler($this, $request, $id, function ($model) {
      $model->price = ShopHelper::getShopReturnRecordPrice($model->count, $model->shop_order_shop_product);
      $model->save();
      ShopHelper::returnProductChangeCount($model->id, 'store');
      ShopHelper::changeShopOrderPrice($model->shop_order->id);
    });
  }

  /**
   * Show
   *
   * @urlParam  shop_return_record required The ID of shop_return_record. Example: 1
   */
  public function show(Request $request, $id = null)
  {
    if (config('stone.mode') == 'cms') {
      return ModelHelper::ws_ShowHandler($this, $request, $id);
    } else if (config('stone.mode') == 'webapi') {
      $auth               = Auth::user();
      $shop_return_record = ShopReturnRecord::where('id', $id)->first();
      if (!$shop_return_record) {
        return response()->json([
          'message' => 'no data.',
        ], 400);
      }
      if ($shop_return_record->user->id != $auth->id) {
        return response()->json([
          'message' => 'invaid scopes',
        ], 400);
      }
      return ModelHelper::ws_ShowHandler($this, $request, $id);
    }
  }

  /**
   * Update
   *
   * @urlParam  shop_return_record required The ID of shop_return_record. Example: 1
   * @bodyParam user int 人員 Example:1
   * @bodyParam shop_order int 訂單 Example:1
   * @bodyParam shop_order_shop_product int 訂單商品 Example:1
   * @bodyParam shop_product int 商品 Example:1
   * @bodyParam count int 數量 Example:1
   * @bodyParam price int 價格 Example:1
   * @bodyParam remark string 備註 Example:remark
   * @bodyParam return_reason string 退貨理由 Example:reason
   */
  public function update(Request $request, $id)
  {
    return ModelHelper::ws_UpdateHandler($this, $request, $id, [], function ($model) {
      ShopHelper::returnProductChangeCount($model->id, 'update');
      ShopHelper::changeShopOrderPrice($model->shop_order->id);
    });
  }

  /**
   * Delete
   *
   * @urlParam  shop_return_record required The ID of shop_return_record. Example: 2
   */
  public function destroy($id)
  {
    return ModelHelper::ws_DestroyHandler($this, $id, function ($model) {
      ShopHelper::destroyReturnRecord($model);
      ShopHelper::changeShopOrderPrice($model->shop_order->id);
    });
  }

  /**
   * Return All
   *
   * @bodyParam shop_orders object 訂單 Example:[1,2,3]
   * @bodyParam remark string 備註 Example:remark
   * @bodyParam return_reason string 退貨理由 Example:remark
   */
  public function return_all(Request $request)
  {
    if (!$request->has('shop_orders') || !count($request->shop_orders)) {
      return response()->json([
        'message' => 'need shop_orders',
      ], 400);
    }
    $order_arr = $request->shop_orders;
    foreach ($order_arr as $order_id) {
      //get shop_order
      $shop_order = ShopOrder::where('id', $order_id)->first();
      if (!$shop_order) {
        return response()->json([
          'message' => 'shop order no data',
        ], 400);
      }
      // get shop_order_shop_products in shop_order
      $shop_order_shop_products = ShopOrderShopProduct::where('shop_order_id', $shop_order->id)->get();
      // create shop_return_record for shop_order_shop_product
      foreach ($shop_order_shop_products as $shop_order_shop_product) {
        //shop_order_shop_product's count > 0
        if ($shop_order_shop_product->count) {
          //create shop_return_record
          $shop_return_record = ShopHelper::createShopReturnRecord($shop_order, $shop_order_shop_product, $request->remark, $request->return_reason, 'return_all');
          //change product stock
          ShopHelper::returnProductChangeCount($shop_return_record->id, 'store');
        }
      }
      // refresh shop order price
      ShopHelper::changeShopOrderPrice($shop_order->id);
    }
    return response()->json([
      "message" => 'shop order returned.',
    ], 201);
  }
}
