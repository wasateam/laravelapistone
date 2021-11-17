<?php

namespace Wasateam\Laravelapistone\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Wasateam\Laravelapistone\Helpers\ModelHelper;

/**
 * @group 商品
 *
 * @authenticated
 *
 * 商品列表API
 */
class ShopProductController extends Controller
{
  public $model                   = 'Wasateam\Laravelapistone\Models\ShopProduct';
  public $name                    = 'shop_product';
  public $resource                = 'Wasateam\Laravelapistone\Resources\ShopProduct';
  public $resource_for_collection = 'Wasateam\Laravelapistone\Resources\ShopProductCollection';
  public $input_fields            = [
    'type',
    'no',
    'name',
    'subtitle',
    'status',
    'on_time',
    'off_time',
    'is_active',
    'spec',
    'cost',
    'price',
    'discount_price',
    'weight_capacity',
    'tax',
    'stock_count',
    'stock_alert_count',
    'max_buyable_count',
    'storage_space',
    'cover_image',
    'images',
    'description',
    'source',
    'store_temperature',
    'ranking_score',
  ];
  public $search_fields = [
    'no',
    'uuid',
    'name',
    'storage_space',
  ];
  public $belongs_to = [
    'shop_product_cover_frame',
  ];
  public $filter_fields = [
    'tax',
    'is_active',
    'status',
    'type',
    'source',
    'store_temperature',
  ];
  public $belongs_to_many = [
    'suggests',
    'shop_classes',
    'shop_subclasses',
  ];
  public $filter_belongs_to_many = [
    'shop_classes',
    'shop_subclasses',
  ];
  public $order_fields = [
    'ranking_score',
    'updated_at',
    'created_at',
    'price',
    'discount_price',
    'stock_count',
  ];
  public $uuid = false;

  public function __construct()
  {
    if (config('stone.shop.uuid')) {
      $this->uuid = true;
    }
    if (config('stone.featured_class')) {
      $this->belongs_to_many[] = 'featured_classes';
    }
    if (config('stone.area')) {
      $this->belongs_to_many[] = 'areas';
      $this->belongs_to_many[] = 'area_sections';
    }
  }
  /**
   * Index
   * @queryParam search string 搜尋字串 No-example
   * @queryParam page int 頁碼  No-example
   * @queryParam shop_classes ids 分類  No-example
   * @queryParam shop_subclasses ids 子分類  No-example
   * @queryParam areas ids 地區  No-example
   * @queryParam area_sections ids 子地區  No-example
   *
   */
  public function index(Request $request, $id = null)
  {
    if (config('stone.mode') == 'cms') {
      return ModelHelper::ws_IndexHandler($this, $request, $id);
    } else if (config('stone.mode') == 'webapi') {
      if (!$request->has('shop_classes') && !$request->has('shop_subclasses')) {
        return response()->json([
          'message' => 'shop_classes or shop_subclasses is required.',
        ], 400);
      }
      return ModelHelper::ws_IndexHandler($this, $request, $id, true, function ($snap) {
        $snap = $snap->where('is_active', 1);
        return $snap;
      });
    }
  }

  /**
   * Store
   *
   * @bodyParam name string 商品名稱 No-example
   * @bodyParam subtitle string 商品副標 No-example
   * @bodyParam on_time datetime 上架時間 No-example
   * @bodyParam off_time datetime 下架時間 No-example
   * @bodyParam is_active boolean 商品狀態 No-example
   * @bodyParam spec string 商品規格 No-example
   * @bodyParam cost int 成本 No-example
   * @bodyParam price int 售價 No-example
   * @bodyParam discount_price int 優惠價 No-example
   * @bodyParam weight_capacity 重量/容量 string No-example
   * @bodyParam tax string 應稅/免稅 No-example
   * @bodyParam stock_count string 庫存數量 No-example
   * @bodyParam stock_alert_count string 庫存預警數量 No-example
   * @bodyParam max_buyable_count string 最大可購買數量 No-example
   * @bodyParam storage_space string 商品儲位 No-example
   * @bodyParam cover_image string 商品封面圖 No-example
   * @bodyParam images string 商品圖 No-example
   * @bodyParam description string 商品簡介 No-example
   * @bodyParam ranking_score string 熱門分數 No-example
   * @bodyParam shop_product_cover_frame id 活動圖框 No-example
   * @bodyParam suggests ids 推薦商品 No-example
   * @bodyParam shop_classes ids 分類 No-example
   * @bodyParam shop_subclasses ids 子分類 No-example
   * @bodyParam areas ids 地區 No-example
   * @bodyParam area_sections ids 子地區 No-example
   */
  public function store(Request $request, $id = null)
  {
    return ModelHelper::ws_StoreHandler($this, $request, $id);
  }

  /**
   * Show
   *
   * @urlParam  shop_product required The ID of shop_product. Example: 1
   */
  public function show(Request $request, $id = null)
  {
    return ModelHelper::ws_ShowHandler($this, $request, $id);
  }

  /**
   * Update
   *
   * @urlParam  shop_product required The ID of shop_product. Example: 1
   * @bodyParam name string 商品名稱 No-example
   * @bodyParam subtitle string 商品副標 No-example
   * @bodyParam on_time datetime 上架時間 No-example
   * @bodyParam off_time datetime 下架時間 No-example
   * @bodyParam is_active boolean 商品狀態 No-example
   * @bodyParam spec string 商品規格 No-example
   * @bodyParam cost int 成本 No-example
   * @bodyParam price int 售價 No-example
   * @bodyParam discount_price int 優惠價 No-example
   * @bodyParam weight_capacity 重量/容量 string No-example
   * @bodyParam tax string 應稅/免稅 No-example
   * @bodyParam stock_count string 庫存數量 No-example
   * @bodyParam stock_alert_count string 庫存預警數量 No-example
   * @bodyParam max_buyable_count string 最大可購買數量 No-example
   * @bodyParam storage_space string 商品儲位 No-example
   * @bodyParam cover_image string 商品封面圖 No-example
   * @bodyParam images string 商品圖 No-example
   * @bodyParam description string 商品簡介 No-example
   * @bodyParam ranking_score string 熱門分數 No-example
   * @bodyParam shop_product_cover_frame id 活動圖框 No-example
   * @bodyParam suggests ids 推薦商品 No-example
   * @bodyParam shop_classes ids 分類 No-example
   * @bodyParam shop_subclasses ids 子分類 No-example
   * @bodyParam areas ids 地區 No-example
   * @bodyParam area_sections ids 子地區 No-example
   */
  public function update(Request $request, $id)
  {
    return ModelHelper::ws_UpdateHandler($this, $request, $id);
  }

  /**
   * Delete
   *
   * @urlParam  shop_product required The ID of shop_product. Example: 2
   */
  public function destroy($id)
  {
    return ModelHelper::ws_DestroyHandler($this, $id);
  }
}
