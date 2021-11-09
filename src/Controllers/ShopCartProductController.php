<?php

namespace Wasateam\Laravelapistone\Controllers;

use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Wasateam\Laravelapistone\Helpers\ModelHelper;
use Wasateam\Laravelapistone\Models\ShopCart;
use Wasateam\Laravelapistone\Models\ShopCartProduct;
use Wasateam\Laravelapistone\Models\ShopProduct;

/**
 * @group 購物車商品
 *
 * @authenticated
 *
 * 購物車商品 API
 */
class ShopCartProductController extends Controller
{
  public $model                   = 'Wasateam\Laravelapistone\Models\ShopCartProduct';
  public $name                    = 'shop_cart_product';
  public $resource                = 'Wasateam\Laravelapistone\Resources\ShopCartProduct';
  public $resource_for_collection = 'Wasateam\Laravelapistone\Resources\ShopCartProductCollection';
  public $input_fields            = [];
  public $belongs_to              = [];
  public $filter_fields           = [
    'status',
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
      $this->belongs_to[]   = 'shop_cart';
      $this->belongs_to[]   = 'shop_product';
    }
  }

  /**
   * Index
   * @queryParam search string 搜尋字串 No-example
   * @queryParam shop_cart ids 購物車  No-example
   * @queryParam status ids 狀態  No-example
   *
   */
  public function index(Request $request, $id = null)
  {
    return ModelHelper::ws_IndexHandler($this, $request, $id);
  }

  /**
   * Auth Product Index
   * @queryParam search string 搜尋字串 No-example
   *
   */
  public function auth_cart_product_index(Request $request, $id = null)
  {
    $auth = Auth::user();
    return ModelHelper::ws_IndexHandler($this, $request, $id, $request->get_all, function ($snap) use ($auth) {
      $snap = $snap->whereHas('shop_cart', function ($query) use ($auth) {
        return $query->where('user_id', $auth->id);
      })->where('status', '==', 1);
    });
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
    return ModelHelper::ws_StoreHandler($this, $request, $id);
  }

  /**
   * Store Auth Cart
   *
   * @bodyParam shop_product int 商品id Example:1
   * @bodyParam count int 數量 Example:1
   */
  public function product_store_auth_cart(Request $request, $id = null)
  {
    $auth           = Auth::user();
    $auth_shop_cart = ShopCart::where('user_id', $auth->id)->first();
    if (!$auth_shop_cart) {
      $auth_shop_cart          = new ShopCart;
      $auth_shop_cart->user_id = $auth->id;
      $auth_shop_cart->save();
    }
    $shop_product = ShopProduct::where('id', $request->shop_product)->where('is_active', '==', 1)->first();
    if (!$shop_product) {
      return response()->json([
        'message' => 'no data.',
      ], 400);
    }
    $request->request->add([
      'shop_cart'      => $auth_shop_cart->id,
      'name'           => $shop_product->name,
      'subtitle'       => $shop_product->subtitle,
      'price'          => $shop_product->price,
      'discount_price' => $shop_product->discount_price,
    ]);
    return ModelHelper::ws_StoreHandler($this, $request, $id);
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