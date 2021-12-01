<?php

namespace Wasateam\Laravelapistone\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Wasateam\Laravelapistone\Helpers\ModelHelper;use Wasateam\Laravelapistone\Imports\ShopProductImport;

/**
 * @group 商品
 *
 * 商品列表API
 *
 * 商品庫存匯入 Import Excel
 * excel 欄位順序 : 系統流水號、商品編號、主分類、次分類、商品名稱、規格、重量、成本、售價、庫存、儲位
 * Import Excel 前端要使用 js 的 const formData = new FormData();
 * formData.append("file", file);
 * header 要帶 "Content-Type": "multipart/form-data",
 *
 * fields ----
 * type 類型
 * order_type 訂單類型 如要建立訂單，商品的訂單類型皆需一致，不然無法建立訂單
 * name 商品名稱
 * subtitle 商品副標
 * on_time 上架時間
 * off_time 下架時間
 * is_active 商品狀態
 * spec 商品規格
 * cost 成本
 * price 售價
 * discount_price 優惠價
 * weight_capacity 重量/容量
 * tax
 * stock_count
 * stock_alert_count
 * max_buyable_count
 * storage_space
 * cover_image
 * images
 * description
 * ranking_score
 * shop_product_cover_frame
 * suggests
 * shop_classes
 * shop_subclasses
 * areas
 * area_sections
 *
 * @authenticated
 */
class ShopProductController extends Controller
{
  public $model                   = 'Wasateam\Laravelapistone\Models\ShopProduct';
  public $name                    = 'shop_product';
  public $resource                = 'Wasateam\Laravelapistone\Resources\ShopProduct';
  public $resource_for_collection = 'Wasateam\Laravelapistone\Resources\ShopProductCollection';
  public $input_fields            = [
    'type',
    'order_type',
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
    'order_type',
    'source',
    'store_temperature',
  ];
  public $belongs_to_many = [
    'suggests',
    'shop_classes',
    'shop_subclasses',
    'featured_classes',
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
  public $validation_rules = [
    'no' => "required|unique:shop_products",
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
   * @queryParam featured_classes ids 精選分類  No-example
   * @queryParam areas ids 地區  No-example
   * @queryParam area_sections ids 子地區  No-example
   * @queryParam type ids 類型  No-example
   * @queryParam order_type ids 訂單類型  No-example
   *
   */
  public function index(Request $request, $id = null)
  {
    if (config('stone.mode') == 'cms') {
      return ModelHelper::ws_IndexHandler($this, $request, $id);
    } else if (config('stone.mode') == 'webapi') {
      if (config('stone.featured_class') && $request->has('featured_classes')) {
        return ModelHelper::ws_IndexHandler($this, $request, $id, true, function ($snap) {
          $snap = $snap->where('is_active', 1);
          return $snap;
        });
      } else if (!$request->has('shop_classes') && !$request->has('shop_subclasses')) {
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
   * @bodyParam type string 類型 Example: 類型
   * @bodyParam order_type string 訂單類型 Example: 訂單類型
   * @bodyParam name string 商品名稱 Example: 商品名稱
   * @bodyParam subtitle string 商品副標 Example: 商品副標
   * @bodyParam on_time datetime 上架時間 Example: 2021-10-10
   * @bodyParam off_time datetime 下架時間 Example: 2021-10-20
   * @bodyParam is_active boolean 商品狀態 Example: 1
   * @bodyParam spec string 商品規格 Example: 五顆裝
   * @bodyParam cost int 成本 Example: 200
   * @bodyParam price int 售價 Example: 300
   * @bodyParam discount_price int 優惠價 Example: 280
   * @bodyParam weight_capacity 重量/容量 string Example: 100g
   * @bodyParam tax string 應稅/免稅 Example: 10
   * @bodyParam stock_count string 庫存數量 Example: 100
   * @bodyParam stock_alert_count string 庫存預警數量 Example: 10
   * @bodyParam max_buyable_count string 最大可購買數量 Example: 10
   * @bodyParam storage_space string 商品儲位 Example: AA
   * @bodyParam cover_image string 商品封面圖 Example:""
   * @bodyParam images string 商品圖 Example:[]
   * @bodyParam description string 商品簡介 Example: 商品簡介
   * @bodyParam ranking_score string 熱門分數 Example: 10
   * @bodyParam shop_product_cover_frame id 活動圖框 Example: 1
   * @bodyParam suggests ids 推薦商品 Example: [1,2]
   * @bodyParam shop_classes ids 分類 Example: [1,2]
   * @bodyParam shop_subclasses ids 子分類 Example: [1,2]
   * @bodyParam areas ids 地區 Example: [1,2]
   * @bodyParam area_sections ids 子地區 Example: [1,2]
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
   * @bodyParam type string 類型 Example: 類型
   * @bodyParam order_type string 訂單類型 Example: 訂單類型
   * @bodyParam name string 商品名稱 Example: 商品名稱
   * @bodyParam subtitle string 商品副標 Example: 商品副標
   * @bodyParam on_time datetime 上架時間 Example: 2021-10-10
   * @bodyParam off_time datetime 下架時間 Example: 2021-10-20
   * @bodyParam is_active boolean 商品狀態 Example: 1
   * @bodyParam spec string 商品規格 Example: 五顆裝
   * @bodyParam cost int 成本 Example: 200
   * @bodyParam price int 售價 Example: 300
   * @bodyParam discount_price int 優惠價 Example: 280
   * @bodyParam weight_capacity 重量/容量 string Example: 100g
   * @bodyParam tax string 應稅/免稅 Example: 10
   * @bodyParam stock_count string 庫存數量 Example: 100
   * @bodyParam stock_alert_count string 庫存預警數量 Example: 10
   * @bodyParam max_buyable_count string 最大可購買數量 Example: 10
   * @bodyParam storage_space string 商品儲位 Example: AA
   * @bodyParam cover_image string 商品封面圖 Example:""
   * @bodyParam images string 商品圖 Example:[]
   * @bodyParam description string 商品簡介 Example: 商品簡介
   * @bodyParam ranking_score string 熱門分數 Example: 10
   * @bodyParam shop_product_cover_frame id 活動圖框 Example: 1
   * @bodyParam suggests ids 推薦商品 Example: [1,2]
   * @bodyParam shop_classes ids 分類 Example: [1,2]
   * @bodyParam shop_subclasses ids 子分類 Example: [1,2]
   * @bodyParam areas ids 地區 Example: [1,2]
   * @bodyParam area_sections ids 子地區 Example: [1,2]
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

  /**
   * Import Excel
   */
  public function import_excel(Request $request)
  {
    Excel::import(new ShopProductImport, $request->file('file'));
    return response()->json([
      'message' => 'import success.',
    ], 201);
  }
}
