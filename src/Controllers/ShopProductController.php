<?php

namespace Wasateam\Laravelapistone\Controllers;

use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Maatwebsite\Excel\Facades\Excel;
use Wasateam\Laravelapistone\Exports\ShopProductExport;
use Wasateam\Laravelapistone\Helpers\ModelHelper;
use Wasateam\Laravelapistone\Imports\ShopProductImport;
use Wasateam\Laravelapistone\Models\ShopProduct;

/**
 * @group 商品
 *
 * 商品列表API
 *
 * 商品庫存狀態篩選 stock_level
 * ~ 1 => 商品庫存未達預警
 * ~ 2 => 商品庫存已達預警
 *
 * 商品庫存匯入 Import Excel
 * excel 欄位順序 : 系統流水號、商品編號、主分類、次分類、商品名稱、規格、重量、成本、售價、庫存、儲位
 * Import Excel 前端要使用 js 的 const formData = new FormData();
 * formData.append("file", file);
 * header 要帶 "Content-Type": "multipart/form-data",
 *
 * 商品匯出 Export Excel Signedurl
 * get_all 可以直接取得所有商品
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
 * weight_capacity_unit 重量/容量單位
 * show_weight_capacity 重量/容量 前台是否顯示
 * tax
 * stock_count
 * stock_alert_count
 * max_buyable_count
 * storage_space 儲位
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
 * freight 運費
 * store_temperature 溫層
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
    'is_shop_productive',
    'spec',
    'cost',
    'price',
    'discount_price',
    'weight_capacity',
    'weight_capacity_unit',
    'show_weight_capacity',
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
    'freight',
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
    'is_shop_productive',
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
      $this->belongs_to_many[]        = 'featured_classes';
      $this->filter_belongs_to_many[] = 'featured_classes';
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
   * @queryParam stock_level ids 篩選庫存狀態  No-example 1,2
   *
   */
  public function index(Request $request, $id = null)
  {
    if (config('stone.mode') == 'cms') {
      return ModelHelper::ws_IndexHandler($this, $request, $id, false, function ($model) use ($request) {
        $stock_level = $request->has('stock_level') ? $request->stock_level : null;
        if ($stock_level) {
          if ($stock_level == 2) {
            return $model->whereRaw('stock_count < stock_alert_count');
          } else if ($stock_level == 1) {
            return $model->whereRaw('stock_count >= stock_alert_count');
          } else {
            return $model;
          }
        } else {
          return $model;
        }
      });
    } else if (config('stone.mode') == 'webapi') {
      if (config('stone.featured_class') && $request->has('featured_classes')) {
        return ModelHelper::ws_IndexHandler($this, $request, $id, true, function ($snap) {
          $snap = $snap->where('is_shop_productive', 1);
          return $snap;
        });
      } else if (!$request->has('shop_classes') && !$request->has('shop_subclasses')) {
        return response()->json([
          'message' => 'shop_classes or shop_subclasses is required.',
        ], 400);
      }
      return ModelHelper::ws_IndexHandler($this, $request, $id, true, function ($snap) {
        $snap = $snap->where('is_shop_productive', 1);
        return $snap;
      });
    }
  }

  /**
   * Store
   *
   * @bodyParam no string 商品編號 Example: Aa123
   * @bodyParam type string 類型 Example: 類型
   * @bodyParam order_type string 訂單類型 Example: 訂單類型
   * @bodyParam name string 商品名稱 Example: 商品名稱
   * @bodyParam subtitle string 商品副標 Example: 商品副標
   * @bodyParam on_time datetime 上架時間 Example: 2021-10-10
   * @bodyParam off_time datetime 下架時間 Example: 2021-10-20
   * @bodyParam is_shop_productive boolean 商品狀態 Example: 1
   * @bodyParam spec string 商品規格 Example: 五顆裝
   * @bodyParam cost int 成本 Example: 200
   * @bodyParam price int 售價 Example: 300
   * @bodyParam discount_price int 優惠價 Example: 280
   * @bodyParam weight_capacity 重量/容量 string Example: 100
   * @bodyParam weight_capacity_unit 重量/容量單位 string Example: kg
   * @bodyParam show_weight_capacity 重量/容量前台是否顯示 string Example: 1
   * @bodyParam tax string 應稅/免稅 Example: 10
   * @bodyParam stock_count string 庫存數量 Example: 100
   * @bodyParam stock_alert_count string 庫存預警數量 Example: 10
   * @bodyParam freight string 運費 Example: 10
   * @bodyParam store_temperature string 溫層 Example: 10
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
   * @bodyParam no string 商品編號 Example: Aa123
   * @bodyParam type string 類型 Example: 類型
   * @bodyParam order_type string 訂單類型 Example: 訂單類型
   * @bodyParam name string 商品名稱 Example: 商品名稱
   * @bodyParam subtitle string 商品副標 Example: 商品副標
   * @bodyParam on_time datetime 上架時間 Example: 2021-10-10
   * @bodyParam off_time datetime 下架時間 Example: 2021-10-20
   * @bodyParam is_shop_productive boolean 商品狀態 Example: 1
   * @bodyParam spec string 商品規格 Example: 五顆裝
   * @bodyParam cost int 成本 Example: 200
   * @bodyParam price int 售價 Example: 300
   * @bodyParam discount_price int 優惠價 Example: 280
   * @bodyParam weight_capacity 重量/容量 string Example: 100g
   * @bodyParam weight_capacity_unit 重量/容量單位 string Example: kg
   * @bodyParam show_weight_capacity 重量/容量前台是否顯示 string Example: 1
   * @bodyParam tax string 應稅/免稅 Example: 10
   * @bodyParam stock_count string 庫存數量 Example: 100
   * @bodyParam stock_alert_count string 庫存預警數量 Example: 10
   * @bodyParam max_buyable_count string 最大可購買數量 Example: 10
   * @bodyParam freight string 運費 Example: 10
   * @bodyParam store_temperature string 溫層 Example: 10
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

  /**
   * Export Excel Signedurl
   *
   * @queryParam shop_classes ids 分類  No-example 1,2,3
   * @queryParam shop_subclasses ids 子分類  No-example 1,2,3
   * @queryParam is_shop_productive number 上架  No-example : 1,0
   * @queryParam get_all number 全抓  No-example : 1,0
   * @queryParam stock_level number 庫存狀態  No-example : 1,2
   * @queryParam start_date date  開始日期  No-example : 1,2
   * @queryParam end_date date 結束日期  No-example : 1,2
   */
  public function export_excel_signedurl(Request $request)
  {
    $shop_classes       = $request->has('shop_classes') ? $request->shop_classes : null;
    $shop_subclasses    = $request->has('shop_subclasses') ? $request->shop_subclasses : null;
    $is_shop_productive = $request->has('is_shop_productive') ? $request->is_shop_productive : null;
    $get_all            = $request->has('get_all') ? $request->get_all : 0;
    $stock_level        = $request->has('stock_level') ? $request->stock_level : null;
    $start_date         = $request->has('start_date') ? $request->start_date : null;
    $end_date           = $request->has('end_date') ? $request->end_date : null;
    return URL::temporarySignedRoute(
      'shop_product_export_excel',
      now()->addMinutes(30),
      ['shop_classes' => $shop_classes, 'shop_subclasses' => $shop_subclasses, 'is_shop_productive' => $is_shop_productive, 'get_all' => $get_all, 'stock_level' => $stock_level, 'start_date' => $start_date, 'end_date' => $end_date]
    );
  }

  /**
   * Export Excel
   */
  public function export_excel(Request $request)
  {
    $shop_classes       = $request->has('shop_classes') ? $request->shop_classes : null;
    $shop_subclasses    = $request->has('shop_subclasses') ? $request->shop_subclasses : null;
    $is_shop_productive = $request->has('is_shop_productive') ? $request->is_shop_productive : null;
    $get_all            = $request->has('get_all') ? $request->get_all : 0;
    $stock_level        = $request->has('stock_level') ? $request->stock_level : null;
    $start_date         = $request->has('start_date') ? $request->start_date : null;
    $end_date           = $request->has('end_date') ? $request->end_date : null;
    return Excel::download(new ShopProductExport($shop_classes, $shop_subclasses, $is_shop_productive, $get_all, $stock_level, $start_date, $end_date), 'shop_products.xlsx');
  }

  /**
   * Collect ShopProduct 加入我的最愛
   *
   * @urlParam shop_product_id int
   *
   * @authenticated
   *
   */
  public function collect_shop_product($shop_product_id)
  {
    $user = Auth::user();
    try {
      if (!$user->shop_products->contains($shop_product_id)) {
        $user->shop_products()->attach($shop_product_id);
      }
    } catch (\Throwable $th) {
      return response()->json([
        'message' => 'shop_product attach error.',
      ], 400);
    }
    return response()->json([
      'message' => 'shop_product attach ok.',
    ]);
  }

  /**
   * Uncollect ShopProduct 移除我的最愛
   *
   * @urlParam shop_product_id int
   *
   * @authenticated
   *
   */
  public function uncollect_shop_procut($shop_product_id)
  {
    $user = Auth::user();
    try {
      $user->shop_products()->detach($shop_product_id);
    } catch (\Throwable $th) {
      return response()->json([
        'message' => 'shop_product detach error.',
      ], 400);
    }
    return response()->json([
      'message' => 'shop_product detach ok.',
    ]);
  }

  /**
   * Collected ShopProduct Index 我的最愛列表
   *
   * @queryParam order_type ids 訂單類型  No-example next-day,pre-order
   *
   * @authenticated
   */
  public function collected_shop_product_index(Request $request)
  {
    $user = Auth::user();
    return ModelHelper::ws_IndexHandler($this, $request, null, $request->get_all, function ($snap) use ($user) {
      $snap
        ->whereHas('users', function ($query) use ($user) {
          $query->where('users.id', $user->id);
        });
      return $snap;
    });
  }

  /**
   * Collected ShopProduct Ids 我的最愛列表ids
   *
   * @authenticated
   *
   */
  public function collected_shop_product_ids(Request $request)
  {
    $user = Auth::user();

    $my_shop_products = ShopProduct::whereHas('users', function ($query) use ($user) {
      $query->where('users.id', $user->id);
    })->get();

    $my_shop_product_ids = $my_shop_products->map(function ($item) {
      return $item->id;
    });

    return response()->json([
      'data' => $my_shop_product_ids,
    ], 200);
  }
}
