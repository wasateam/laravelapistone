<?php

namespace Wasateam\Laravelapistone\Controllers;

use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Http\Request;
use Wasateam\Laravelapistone\Helpers\ModelHelper;
use Wasateam\Laravelapistone\Models\ShopCart;

/**
 * @group ShopCart 購物車
 *
 * 購物車 API
 *
 * user 購物車所屬使用者
 *
 * Auth Cart 用來取得當前登入者的購物車
 *
 * @authenticated
 */
class ShopCartController extends Controller
{
  public $model                   = 'Wasateam\Laravelapistone\Models\ShopCart';
  public $name                    = 'shop_cart';
  public $resource                = 'Wasateam\Laravelapistone\Resources\ShopCart';
  public $resource_for_collection = 'Wasateam\Laravelapistone\Resources\ShopCartCollection';
  public $input_fields            = [];
  public $belongs_to              = [
    'user',
  ];
  public $filter_belongs_to = [
    'user',
  ];

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
   * @bodyParam user id 使用者 No-example
   */
  public function store(Request $request, $id = null)
  {
    return ModelHelper::ws_StoreHandler($this, $request, $id);
  }

  /**
   * Show
   *
   * @urlParam  shop_cart required The ID of shop_cart. Example: 1
   */
  public function show(Request $request, $id = null)
  {
    return ModelHelper::ws_ShowHandler($this, $request, $id);
  }

  /**
   * Update
   *
   * @urlParam  shop_cart required The ID of shop_cart. Example: 1
   * @bodyParam user id 使用者 No-example
   */
  public function update(Request $request, $id)
  {
    return ModelHelper::ws_UpdateHandler($this, $request, $id);
  }

  /**
   * Delete
   *
   * @urlParam  shop_cart required The ID of shop_cart. Example: 2
   */
  public function destroy($id)
  {
    return ModelHelper::ws_DestroyHandler($this, $id);
  }

  /**
   * Auth Cart
   */
  public function auth_cart(Request $request)
  {
    $auth           = Auth::user();
    $auth_shop_cart = ShopCart::where('user_id', $auth->id)->first();
    if (!$auth_shop_cart) {
      $auth_shop_cart          = new ShopCart;
      $auth_shop_cart->user_id = $auth->id;
      $auth_shop_cart->save();
    }
    return new $this->resource($auth_shop_cart);
  }
}
