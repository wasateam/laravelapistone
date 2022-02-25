<?php

namespace Wasateam\Laravelapistone\Controllers;

use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Http\Request;
use Wasateam\Laravelapistone\Helpers\ModelHelper;
use Wasateam\Laravelapistone\Helpers\ShopHelper;
use Wasateam\Laravelapistone\Models\ShopCart;
use Wasateam\Laravelapistone\Models\ShopCartProduct;
use Wasateam\Laravelapistone\Models\ShopProduct;
use Wasateam\Laravelapistone\Models\ShopProductSpec;

/**
 * @group ShopCartProduct 購物車商品
 *
 * 購物車商品 API
 *
 * name 名稱
 * subtitle 副標
 * count 數量
 * price 價格
 * dicount_price 優惠價格
 * order_type 訂單類型
 * shop_cart 所屬購物車
 * shop_porduct 所屬商品
 * shop_porduct_sepc 所屬商品規格
 *
 *
 * @authenticated
 */
class ShopCartProductController extends Controller
{
  public $model                   = 'Wasateam\Laravelapistone\Models\ShopCartProduct';
  public $name                    = 'shop_cart_product';
  public $resource                = 'Wasateam\Laravelapistone\Resources\ShopCartProduct';
  public $resource_for_collection = 'Wasateam\Laravelapistone\Resources\ShopCartProductCollection';
  public $input_fields            = [
    'count',
  ];
  public $belongs_to = [
  ];
  public $filter_fields = [
    'status',
    'order_type',
  ];
  public $filter_belongs_to = [
    'shop_cart',
  ];
  public $search_fields = [
    'name',
  ];

  public function __construct()
  {
    if (config('stone.mode') == 'cms') {
      $this->input_fields[] = 'name';
      $this->input_fields[] = 'subtitle';
      $this->input_fields[] = 'count';
      $this->input_fields[] = 'price';
      $this->input_fields[] = 'discount_price';
      $this->input_fields[] = 'order_type';
      $this->belongs_to[]   = 'shop_cart';
      $this->belongs_to[]   = 'shop_product';
      $this->belongs_to[]   = 'shop_product_spec';
    }
  }

  /**
   * Index
   * @queryParam search string 搜尋字串 No-example
   * @queryParam shop_cart ids 購物車  No-example
   * @queryParam status ids 狀態  No-example
   * @queryParam order_type ids 訂單類型  No-example
   *
   */
  public function index(Request $request, $id = null)
  {
    if (config('stone.mode') == 'cms') {
      return ModelHelper::ws_IndexHandler($this, $request, $id);
    } else if (config('stone.mode') == 'webapi') {
      $user = Auth::user();
      return ModelHelper::ws_IndexHandler($this, $request, $id, true, function ($snap) use ($user) {
        $snap = $snap->whereHas('shop_cart', function ($query) use ($user) {
          return $query->where('user_id', $user->id);
        })->where('status', 1);
        return $snap;
      });
    }
  }

  /**
   * Store
   *
   * @bodyParam name string 商品名稱 Example:name
   * @bodyParam subtitle string 商品副標 Example:subtitle
   * @bodyParam price int 售價 Example:100
   * @bodyParam discount_price int 優惠價 Example:99
   * @bodyParam count int 數量 Example:1
   * @bodyParam shop_cart int 購物車 Example:1
   * @bodyParam shop_product int 產品 Example:1
   */
  public function store(Request $request, $id = null)
  {
    if (config('stone.mode') == 'cms') {
      return ModelHelper::ws_StoreHandler($this, $request, $id);
    } else if (config('stone.mode') == 'webapi') {

      $user         = Auth::user();
      $shop_cart    = ShopHelper::get_user_shop_cart($user);
      $shop_product = ShopProduct::where('id', $request->shop_product)->where('is_active', 1)->first();
      if (!$shop_product) {
        throw new \Wasateam\Laravelapistone\Exceptions\FindNoDataException('shop_product');
      }
      $shop_cart_product_query = ShopCartProduct::where('shop_product_id', $request->shop_product)->where('status', 1)->where('user_id', $user->id);
      $shop_product_spec       = $request->has('shop_product_spec') ? $request->has('shop_product_spec') : null;
      if ($shop_product_spec) {
        $shop_cart_product_query->where('shop_product_spec_id', $request->shop_product_spec);
        $shop_product_spec = ShopProductSpec::where('id', $request->shop_product_spec)->where('shop_product_id', $request->shop_product)->first();
        if (!$shop_product_spec) {
          throw new \Wasateam\Laravelapistone\Exceptions\FindNoDataException('shop_product_spec');
        }
      }
      $shop_cart_product = $shop_cart_product_query->first();

      $ori_count = $shop_cart_product ? $shop_cart_product->count : 0;
      $to_count  = $ori_count + $request->count;

      if ($shop_product_spec) {
        if ($to_count > $shop_product_spec->stock_count) {
          throw new \Wasateam\Laravelapistone\Exceptions\FindNoDataException('shop_product_spec', 'shop_product_spec', $shop_product_spec->id);
        }
      } else {
        if ($to_count > $shop_product->stock_count) {
          throw new \Wasateam\Laravelapistone\Exceptions\FindNoDataException('shop_product', 'shop_product', $shop_product->id);
        }
      }
      if (!$shop_cart_product) {
        $shop_cart_product = new ShopCartProduct();
      }
      $shop_cart_product->count           = $to_count;
      $shop_cart_product->shop_cart_id    = $shop_cart->id;
      $shop_cart_product->shop_product_id = $shop_product->id;
      if ($request->has('shop_product_spec')) {
        $shop_cart_product->shop_product_spec_id = $shop_product_spec->id;
        $shop_cart_product->price                = $shop_product_spec->price;
        $shop_cart_product->discount_price       = $shop_product_spec->discount_price;
      } else {
        $shop_cart_product->price          = $shop_product->price;
        $shop_cart_product->discount_price = $shop_product->discount_price;
      }
      $shop_cart_product->name       = $shop_product->name;
      $shop_cart_product->subtitle   = $shop_product->subtitle;
      $shop_cart_product->order_type = $shop_product->order_type;
      $shop_cart_product->user_id    = $user->id;
      $shop_cart_product->save();

      return response()->json([
        'shop_cart'         => $shop_cart,
        'shop_cart_product' => $shop_cart_product,
      ], 200);
    }
  }

  /**
   * Add Auth Cart
   *
   * @bodyParam shop_product int 商品id Example:1
   * @bodyParam shop_product_spec int 商品規格id Example:1
   * @bodyParam count int 數量 Example:1
   */
  public function product_store_auth_cart(Request $request, $id = null)
  {

    $user         = Auth::user();
    $shop_cart    = ShopHelper::get_user_shop_cart($user);
    $shop_product = ShopProduct::where('id', $request->shop_product)->where('is_active', 1)->first();
    if (!$shop_product) {
      throw new \Wasateam\Laravelapistone\Exceptions\FindNoDataException('shop_product');
    }
    $shop_cart_product_query = ShopCartProduct::where('shop_product_id', $request->shop_product)->where('status', 1)->where('user_id', $user->id);
    if ($request->has('shop_product_spec')) {
      $shop_cart_product_query->where('shop_product_spec_id', $request->shop_product_spec);
      $shop_product_spec = ShopProductSpec::where('id', $request->shop_product_spec)->where('shop_product_id', $request->shop_product)->first();
      if (!$shop_product_spec) {
        throw new \Wasateam\Laravelapistone\Exceptions\FindNoDataException('shop_product_spec');
      }
    }
    $shop_cart_product = $shop_cart_product_query->first();

    $ori_count = $shop_cart_product ? $shop_cart_product->count : 0;
    $to_count  = $ori_count + $request->count;

    if ($request->has('shop_product_spec')) {
      if ($to_count > $shop_product_spec->stock_count) {
        return response()->json([
          'message' => 'products not enough.',
        ], 400);
      }
    } else {
      if ($to_count > $shop_product->stock_count) {
        return response()->json([
          'message' => 'products not enough.',
        ], 400);
      }
    }
    if (!$shop_cart_product) {
      $shop_cart_product = new ShopCartProduct();
    }
    $shop_cart_product->count           = $to_count;
    $shop_cart_product->shop_cart_id    = $shop_cart->id;
    $shop_cart_product->shop_product_id = $shop_product->id;
    if ($request->has('shop_product_spec')) {
      $shop_cart_product->shop_product_spec_id = $shop_product_spec->id;
      $shop_cart_product->price                = $shop_product_spec->price;
      $shop_cart_product->discount_price       = $shop_product_spec->discount_price;
    } else {
      $shop_cart_product->price          = $shop_product->price;
      $shop_cart_product->discount_price = $shop_product->discount_price;
    }
    $shop_cart_product->name       = $shop_product->name;
    $shop_cart_product->subtitle   = $shop_product->subtitle;
    $shop_cart_product->order_type = $shop_product->order_type;
    $shop_cart_product->user_id    = $user->id;
    $shop_cart_product->save();

    $auth           = Auth::user();
    $auth_shop_cart = ShopCart::where('user_id', $auth->id)->first();
    if (!$auth_shop_cart) {
      $auth_shop_cart          = new ShopCart;
      $auth_shop_cart->user_id = $auth->id;
      $auth_shop_cart->save();
    }
  }

  /**
   * Update Auth Cart Product
   *
   * @bodyParam count int 數量 Example:1
   */
  public function update_auth_cart_product(Request $request, $id = null)
  {
    $auth              = Auth::user();
    $shop_cart_product = ShopCartProduct::where('id', $id)->where('status', 1)->whereHas('shop_cart', function ($query) use ($auth) {
      return $query->where('user_id', $auth->id);
    })->first();
    if (!$shop_cart_product) {
      return response()->json([
        'message' => 'no data.',
      ], 400);
    }
    $stock_count = 0;
    if (isset($shop_cart_product->shop_product_spec)) {
      $stock_count = $shop_cart_product->shop_product_spec->stock_count;
    } else {
      $stock_count = $shop_cart_product->shop_product->stock_count;
    }
    if ($stock_count < $request->count) {
      return response()->json([
        'message' => 'products not enough.',
      ], 400);
    }
    return ModelHelper::ws_UpdateHandler($this, $request, $shop_cart_product->id);
  }

  /**
   * Show
   *
   * @urlParam  shop_cart_product required The ID of shop_cart_product. Example: 1
   */
  public function show(Request $request, $id = null)
  {
    return ModelHelper::ws_ShowHandler($this, $request, $id);
  }

  /**
   * Update
   *
   * @urlParam  shop_cart_product required The ID of shop_cart_product. Example: 1
   * @bodyParam name string 商品名稱 Example:name
   * @bodyParam subtitle string 商品副標 Example:subtitle
   * @bodyParam price int 售價 Example:100
   * @bodyParam discount_price int 優惠價 Example:99
   * @bodyParam count int 數量 Example:1
   */
  public function update(Request $request, $id)
  {
    return ModelHelper::ws_UpdateHandler($this, $request, $id);
  }

  /**
   * Delete
   *
   * @urlParam  shop_cart_product required The ID of shop_cart_product. Example: 2
   */
  public function destroy($id)
  {
    return ModelHelper::ws_DestroyHandler($this, $id);
  }

  /**
   * Disabled
   *
   * @urlParam  shop_cart_product required The ID of shop_cart_product. Example: 2
   */
  public function disabled($id)
  {
    $shop_cart_product = ShopCartProduct::where('id', $id)->first();
    if (!$shop_cart_product) {
      return response()->json([
        'message' => 'no data.',
      ], 400);
    }
    $shop_cart_product->status = 0;
    $shop_cart_product->save();

    return response()->json([
      "message" => 'data disabled.',
    ], 200);
  }
}
