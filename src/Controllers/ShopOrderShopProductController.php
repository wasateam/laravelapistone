<?php

namespace Wasateam\Laravelapistone\Controllers;

use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Http\Request;
use Wasateam\Laravelapistone\Helpers\ModelHelper;
use Wasateam\Laravelapistone\Models\ShopOrder;
use Wasateam\Laravelapistone\Models\ShopProduct;

/**
 * @group ShopOrderShopProduct 訂單商品
 *
 * 訂單商品 API
 *
 * no 商品編號
 * name 商品名稱
 * subtitle 商品副標
 * count 數量
 * original_count 原始數量
 * price 價格
 * discount_price 優惠價
 * spec 商品規格
 * weight_capacity 重量/容量
 * weight_capacity_unit 重量/容量單位
 * cost 成本
 * type 類型
 * order_type 訂單類型
 * cover_image 封面照片
 * shop_product 綁定之商品
 * shop_cart_product 綁定之購物車商品
 * shop_order 綁定之訂單
 * shop_order_shop_product_spec 訂單商品規格
 * shop_order_shop_product_spec_setting 訂單商品規格設定
 * shop_order_shop_product_spec_setting_item 訂單商品規格項目
 * storage_space 儲位
 *
 * @authenticated
 */
class ShopOrderShopProductController extends Controller
{
  public $model                   = 'Wasateam\Laravelapistone\Models\ShopOrderShopProduct';
  public $name                    = 'shop_order_shop_product';
  public $resource                = 'Wasateam\Laravelapistone\Resources\ShopOrderShopProduct';
  public $resource_for_collection = 'Wasateam\Laravelapistone\Resources\ShopOrderShopProductCollection';
  public $input_fields            = [];
  public $filter_fields           = [
    'type',
    'order_type',
  ];
  public $belongs_to        = [];
  public $filter_belongs_to = [
    'shop_order',
  ];

  public function __construct()
  {
    if (config('stone.mode') == 'cms') {
      $this->input_fields[]      = 'name';
      $this->input_fields[]      = 'subtitle';
      $this->input_fields[]      = 'count';
      $this->input_fields[]      = 'original_count';
      $this->input_fields[]      = 'price';
      $this->input_fields[]      = 'spec';
      $this->input_fields[]      = 'weight_capacity';
      $this->input_fields[]      = 'cost';
      $this->input_fields[]      = 'type';
      $this->input_fields[]      = 'order_type';
      $this->input_fields[]      = 'cover_image';
      $this->input_fields[]      = 'freight';
      $this->belongs_to[]        = 'shop_product';
      $this->belongs_to[]        = 'shop_cart_product';
      $this->belongs_to[]        = 'shop_order';
      $this->belongs_to[]        = 'shop_order_shop_product_spec';
      $this->belongs_to_many[]   = 'shop_order_shop_product_spec_settings';
      $this->belongs_to_many[]   = 'shop_order_shop_product_spec_setting_items';
      $this->filter_belongs_to[] = 'shop_product';
    }
    if (config('stone.shop.discount_price')) {
      $input_fields[] = 'discount_price';
    }
  }

  /**
   * Index
   * @queryParam shop_product ids 購物車  No-example
   * @queryParam shop_order ids 購物車  No-example
   * @queryParam order_type ids 訂單類型  No-example
   *
   */
  public function index(Request $request, $id = null)
  {
    if (config('stone.mode') == 'cms') {
      return ModelHelper::ws_IndexHandler($this, $request, $id);
    } else if (config('stone.mode') == 'webapi') {
      //params 須帶shop_order
      $auth = Auth::user();
      if (!$request->has('shop_order')) {
        return response()->json([
          'message' => 'shop_order is required.',
        ], 400);
      }
      //一次取一筆訂單
      $shop_order_ids = array_map('intval', explode(',', $request->shop_order));
      if (count($shop_order_ids) > 1) {
        return response()->json([
          'message' => 'shop_order needs to be single.',
        ], 400);
      }
      //是否為自己訂單
      $shop_order = ShopOrder::where('id', $request->shop_product)->first();
      if ($shop_order->user->id != $auth->id) {
        return response()->json([
          'message' => 'invaid scopes',
        ], 400);
      }
      return ModelHelper::ws_IndexHandler($this, $request, $id, true);
    }
  }

  /**
   * Store
   *
   * @bodyParam count int 數量 Example:1
   * @bodyParam shop_order int 購物車 Example:1
   * @bodyParam shop_product int 產品 Example:1
   * @bodyParam shop_cart_product int 購物車產品 Example:1
   */
  public function store(Request $request, $id = null)
  {
    $shop_product = ShopProduct::where('id', $request->shop_product)->where('is_active', '==', 1)->first();
    if (!$shop_product) {
      return response()->json([
        'message' => 'no data.',
      ], 400);
    }
    $request->request->add([
      'name'            => $shop_product->name,
      'type'            => $shop_product->type,
      'subtitle'        => $shop_product->subtitle,
      'price'           => $shop_product->price,
      'discount_price'  => $shop_product->discount_price,
      'spec'            => $shop_product->spec,
      'weight_capacity' => $shop_product->weight_capacity,
      'cover_image'     => $shop_product->cover_image,
      'cost'            => $shop_product->cost,
      'order_type'      => $shop_product->order_type,
      'original_count'  => $request->count,
    ]);
    //create shop_order_shop_product_spec
    //create shop_order_shop_product_spec_setting
    //create shop_order_shop_product_spec_setting_item
    if (config('stone.mode') == 'cms') {
      return ModelHelper::ws_StoreHandler($this, $request, $id);
    } else if (config('stone.mode') == 'webapi') {
      $shop_order = ShopOrder::where('id', $request->shop_order)->first();
      $auth       = Auth::user();
      if ($shop_order->user->id != $auth->id) {
        return response()->json([
          'message' => 'invaid scopes',
        ], 400);
      }
      return ModelHelper::ws_StoreHandler($this, $request, $id, function ($model) {
        $shop_product              = ShopProduct::where('id', $model->shop_product->id)->first();
        $stock_count               = $shop_product->stock_count - $model->count;
        $shop_product->stock_count = $stock_count;
        $shop_product->save();
      });

    }

  }

  /**
   * Show
   *
   * @urlParam  shop_order_shop_product required The ID of shop_order_shop_product. Example: 1
   */
  public function show(Request $request, $id = null)
  {
    return ModelHelper::ws_ShowHandler($this, $request, $id);
  }

  /**
   * Update
   *
   * @urlParam  shop_order_shop_product required The ID of shop_order_shop_product. Example: 1
   * @bodyParam count int 數量 Example:1
   * @bodyParam shop_order int 購物車 Example:1
   * @bodyParam shop_product int 產品 Example:1
   * @bodyParam shop_cart_product int 購物車產品 Example:1
   */
  public function update(Request $request, $id)
  {
    $shop_product = ShopProduct::where('id', $request->shop_product)->where('is_active', '==', 1)->first();
    if (!$shop_product) {
      return response()->json([
        'message' => 'no data.',
      ], 400);
    }
    $request->request->add([
      'name'            => $shop_product->name,
      'type'            => $shop_product->type,
      'subtitle'        => $shop_product->subtitle,
      'price'           => $shop_product->price,
      'discount_price'  => $shop_product->discount_price,
      'spec'            => $shop_product->spec,
      'weight_capacity' => $shop_product->weight_capacity,
      'cover_image'     => $shop_product->cover_image,
      'cost'            => $shop_product->cost,
      'order_type'      => $shop_product->order_type,
      'original_count'  => $request->count,
    ]);
    return ModelHelper::ws_UpdateHandler($this, $request, $id);
  }

  /**
   * Delete
   *
   * @urlParam  shop_order_shop_product required The ID of shop_order_shop_product. Example: 2
   */
  public function destroy($id)
  {
    return ModelHelper::ws_DestroyHandler($this, $id);
  }

}
