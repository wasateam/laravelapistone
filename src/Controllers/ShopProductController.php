<?php

namespace Wasateam\Laravelapistone\Controllers;

use App\Http\Controllers\Controller;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Maatwebsite\Excel\Facades\Excel;
use Wasateam\Laravelapistone\Helpers\ModelHelper;
use Wasateam\Laravelapistone\Helpers\ShopHelper;
use Wasateam\Laravelapistone\Models\ShopProduct;

/**
 * @group ShopProduct 商品
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
 * ~ next-day 隔日配
 * ~ pre-order 預購
 * name 商品名稱
 * subtitle 商品副標
 * on_time 上架時間
 * off_time 下架時間
 * is_active 商品狀態
 * ~ 0 未上架
 * ~ 1 上架
 * spec 商品規格
 * cost 成本
 * price 售價
 * discount_price 優惠價
 * weight_capacity 重量/容量
 * weight_capacity_unit 重量/容量單位
 * show_weight_capacity 重量/容量 前台是否顯示
 * tax 稅
 * stock_count 庫存
 * stock_alert_count 庫存警示數量
 * max_buyable_count 最大購買數量
 * storage_space 儲位
 * cover_image 封面圖片
 * images 圖片們
 * description 敘述
 * ranking_score
 * shop_product_cover_frame 綁定之活動框
 * suggests 綁定之建議商品
 * shop_classes 綁定之商品類別
 * shop_subclasses 綁定之商品子類別
 * areas 綁定之地區
 * area_sections 綁定之子地區
 * freight 運費
 * store_temperature 溫層
 * shop_product_spec_settings 商品規格設定
 * purchaser 採購人
 * cold_chain_type 溫層
 * ~ 冷凍 freezing
 * ~ 常溫 normal
 * ~ 低溫 cold
 *
 *
 * Store/Update
 * shop_product_spec_settings 底下需要帶 shop_product_spec_setting_items
 * 順序要跟 shop_product_specs的順序一樣，如果是尚未建立的規格跟規格設定不用帶id，
 * 如果是已經建立過的要記得帶規格、規格設定本身的id
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
    // 'discount_price',
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
    'purchaser',
    'cold_chain_type',
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
    'purchaser',
    'cold_chain_type',
  ];
  public $belongs_to_many = [
    'suggests',
    'shop_classes',
    'shop_subclasses',
  ];
  public $filter_belongs_to = [
    'shop_product_cover_frame',
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
    // 'discount_price',
    'stock_count',
    'storage_space',
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
    if (config('stone.shop.discount_price')) {
      $input_fields[] = 'discount_price';
      $order_fields[] = 'discount_price';
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
   * @queryParam stock_level ids 篩選庫存狀態  No-example
   * @queryParam shop_product_cover_frame 篩選活動框  No-example
   * @queryParam purchaser 採購人 No-example
   * @queryParam cold_chain_type 溫層 No-example
   * @queryParam has_stock boolean 有庫存 Example:1
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
      return ModelHelper::ws_IndexHandler($this, $request, $id, true, function ($snap) use ($request) {
        if (!$request->filled('shop_classes') &&
          !$request->filled('shop_subclasses') &&
          !$request->filled('featured_classes')) {
          throw new \Wasateam\Laravelapistone\Exceptions\ParamRequiredException('shop_classes, shop_subclasses or featured_classes');
        }
        $snap = $snap->where('is_active', 1)
          ->where(function ($query) {
            $query->where(function ($query) {
              $now = Carbon::now();
              $query->where('off_time', '>', $now);
            });
            $query->orWhereNull('off_time');
          });
        if ($request->filled('has_stock')) {
          $snap = $snap->where(function ($query) {
            $query->where('stock_count', '>', 0)
              ->orWhereHas('has_stock_shop_product_specs');
          });
        }
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
   * @bodyParam is_active boolean 商品狀態 Example: 1
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
   * @bodyParam shop_product_spec_settings object 商品規格設定 Example:[{"name":"name","sq":"1","shop_product_spec_setting_items":[{"name":"name","sq":"1"}]}]
   * @bodyParam shop_product_specs object 商品規格 Example: [{"no":"SexyMonkey","cost":"100","price":"1000","discount_price":"900","start_at":"2021-10-10","end_at":"2021-10-20","freight":"100","max_buyable_count":"100","storage_space":"AA","stock_count":"1000","stock_alert_count":"100"}]
   * @bodyParam purchaser 採購人 Example: 我是一顆蛋
   * @bodyParam cold_chain_type 溫層 Example: freezing
   */
  public function store(Request $request, $id = null)
  {
    if ($request->has('shop_product_spec_settings') && $request->has('shop_product_specs')) {
      $is_match = ShopHelper::checkSettingItemsMatchSpecs($request->shop_product_spec_settings, $request->shop_product_specs);
      if (!$is_match) {
        return response()->json([
          'message' => 'specs count is not correct',
        ], 400);
      }
    }
    if ($request->filled('storage_space') && $requst->filled('on_time')) {
      ShopHelper::ShopProductStorageSpaceCheck($request->storage_space, $request->on_time, $request->off_time);
    }
    return ModelHelper::ws_StoreHandler($this, $request, $id, function ($model) use ($request) {
      //shop_product_spec_settings
      if ($request->has('shop_product_spec_settings') && $request->has('shop_product_specs')) {
        ShopHelper::shopProductCreateSpec($request->shop_product_spec_settings, $request->shop_product_specs, $model->id);
      }
    });
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
   * @bodyParam is_active boolean 商品狀態 Example: 1
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
   * @bodyParam shop_product_spec_settings object 商品規格設定 Example:[{"name":"name","sq":"1","shop_product_spec_setting_items":[{"name":"name","sq":"1"}]}]
   * @bodyParam shop_product_specs object 商品規格 Example: [{"no":"SexyMonkey","cost":"100","price":"1000","discount_price":"900","start_at":"2021-10-10","end_at":"2021-10-20","freight":"100","max_buyable_count":"100","storage_space":"AA","stock_count":"1000","stock_alert_count":"100"}]
   * @bodyParam purchaser 採購人 Example: 我是一顆蛋
   * @bodyParam cold_chain_type 溫層 Example: freezing
   */
  public function update(Request $request, $id)
  {
    if ($request->has('shop_product_spec_settings') && $request->has('shop_product_specs')) {
      $is_match = ShopHelper::checkSettingItemsMatchSpecs($request->shop_product_spec_settings, $request->shop_product_specs);
      if (!$is_match) {
        return response()->json([
          'message' => 'specs count is not correct',
        ], 400);
      }
    }
    return ModelHelper::ws_UpdateHandler($this, $request, $id, [], function ($model) use ($request) {
      //shop_product_spec_settings
      if ($request->has('shop_product_spec_settings') && $request->has('shop_product_specs')) {
        ShopHelper::shopProductDeleteSpec($request->shop_product_spec_settings, $request->shop_product_specs, $model->id);
        ShopHelper::shopProductCreateSpec($request->shop_product_spec_settings, $request->shop_product_specs, $model->id);
      }
    });
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
    ModelHelper::ws_ImportExcelHandler($request, function ($datas) {
      foreach ($datas as $data) {
        if ($data[0] == '系統流水號') {
          continue;
        }
        if ($data[2]) {
          $shop_product = ShopProduct::where('no', $data[2])->first();
          if ($shop_product) {
            $import_record                  = new ShopProductImportRecord;
            $import_record->shop_product_id = $shop_product->id;
            $import_record->no              = $data[2];
            $import_record->stock_count     = $data[8];
            $import_record->storage_space   = $data[9];
            $import_record->save();
            $shop_product->stock_count   = $data[8];
            $shop_product->storage_space = $data[9];
            $shop_product->save();
          }
        }
      }
    });
    return response()->json([
      'message' => 'import success.',
    ], 201);
  }

  /**
   * Export Excel Signedurl
   *
   * @queryParam shop_classes ids 分類  No-example 1,2,3
   * @queryParam shop_subclasses ids 子分類  No-example 1,2,3
   * @queryParam is_active number 上架  No-example : 1,0
   * @queryParam get_all number 全抓  No-example : 1,0
   * @queryParam stock_level number 庫存狀態  No-example : 1,2
   * @queryParam start_date date  開始日期  No-example : 1,2
   * @queryParam end_date date 結束日期  No-example : 1,2
   */
  public function export_excel_signedurl(Request $request)
  {
    // $shop_classes    = $request->has('shop_classes') ? $request->shop_classes : null;
    // $shop_subclasses = $request->has('shop_subclasses') ? $request->shop_subclasses : null;
    // $is_active       = $request->has('is_active') ? $request->is_active : null;
    // $get_all         = $request->has('get_all') ? $request->get_all : 0;
    // $stock_level     = $request->has('stock_level') ? $request->stock_level : null;
    // $start_date      = $request->has('start_date') ? $request->start_date : null;
    // $end_date        = $request->has('end_date') ? $request->end_date : null;
    // return URL::temporarySignedRoute(
    //   'shop_product_export_excel',
    //   now()->addMinutes(30),
    //   ['shop_classes' => $shop_classes, 'shop_subclasses' => $shop_subclasses, 'is_active' => $is_active, 'get_all' => $get_all, 'stock_level' => $stock_level, 'start_date' => $start_date, 'end_date' => $end_date]
    // );

    return ModelHelper::ws_ExportExcelSignedurlHandler($this, $request);
  }

  /**
   * Export Excel
   */
  public function export_excel(Request $request)
  {
    $date_arr    = array_map('intval', explode(',', $request->created_at));
    $sales_title = '當日銷售數量';
    if (count($date_arr) > 1) {
      $sales_title = "銷售數量";
    }

    $headings = [
      "系統流水號",
      "商品編號",
      "商品名稱",
      "規格",
      "重量/容量",
      "成本",
      "售價",
      "庫存",
      "儲位",
      "採購者姓名",
      $sales_title,
    ];

    return ModelHelper::ws_ExportExcelHandler(
      $this,
      $request,
      $headings,
      function ($model) use ($request) {
        $weight      = $model->weight_capacity . ' ' . $model->weight_capacity_unit;
        $date_arr    = explode(',', $request->created_at);
        $sales_count = ShopHelper::getShopProductSalesCount($model->id, $date_arr[0], $date_arr[1]);
        $map         = [
          $model->uuid,
          $model->no,
          $model->name,
          $model->spec,
          $weight,
          $model->cost,
          $model->price,
          $model->stock_count,
          $model->storage_space,
          $model->purchaser,
          $sales_count,
        ];
        return $map;
      }
    );
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
