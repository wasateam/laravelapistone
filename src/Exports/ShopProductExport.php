<?php

namespace Wasateam\Laravelapistone\Exports;

use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Wasateam\Laravelapistone\Helpers\ShopHelper;
use Wasateam\Laravelapistone\Models\ShopOrderShopProduct;
use Wasateam\Laravelapistone\Models\ShopProduct;

class ShopProductExport implements WithMapping, WithHeadings, FromCollection, ShouldAutoSize
{

  protected $shop_classes;
  protected $shop_subclasses;
  protected $is_active;
  protected $get_all;
  protected $stock_level;
  protected $start_date;
  protected $end_date;

  public function __construct($shop_classes, $shop_subclasses, $is_active, $get_all, $stock_level, $start_date, $end_date)
  {
    $this->shop_classes    = $shop_classes;
    $this->shop_subclasses = $shop_subclasses;
    $this->is_active       = $is_active;
    $this->get_all         = $get_all;
    $this->stock_level     = $stock_level;
    $this->start_date      = $start_date ? $start_date : Carbon::now()->format('Y-m-d');
    $this->end_date        = $end_date ? $end_date : Carbon::now()->format('Y-m-d');
  }

  /**
   * @return \Illuminate\Support\Collection
   */
  public function collection()
  {
    if ($this->get_all) {
      return ShopProduct::all();
    } else {
      $is_active     = $this->is_active ? 1 : 0;
      $shop_products = ShopProduct::where('is_active', $is_active);
      $start_date    = $this->start_date;
      $end_date      = $this->end_date;
      //select shop_classes
      if ($this->shop_classes) {
        $item_arr      = array_map('intval', explode(',', $this->shop_classes));
        $shop_products = $shop_products->with('shop_classes')->whereHas('shop_classes', function ($query) use ($item_arr) {
          return $query->whereIn('id', $item_arr);
        });
      }
      // select shop_subclasses
      if ($this->shop_subclasses) {
        $item_arr      = array_map('intval', explode(',', $this->shop_subclasses));
        $shop_products = $shop_products->with('shop_subclasses')->whereHas('shop_subclasses', function ($query) use ($item_arr) {
          return $query->whereIn('id', $item_arr);
        });
      }
      //select stock_level
      if ($this->stock_level) {
        if ($this->stock_level == 2) {
          $shop_products = $shop_products->whereRaw('stock_count < stock_alert_count');
        } else if ($this->stock_level == 1) {
          $shop_products = $shop_products->whereRaw('stock_count >= stock_alert_count');
        }
      }
      //select start_date,end_date
      // if ($this->start_date && $this->end_date) {
      //   $shop_products = $shop_products->withCount(['shop_order_shop_products' => function ($query) use ($start_date, $end_date) {
      //     return $query->whereDate('created_at', '<=', $this->$end_date)->whereDate('created_at', '>=', $this->$start_date);
      //   }]);
      // }
      return $shop_products->get();
    }
  }

  public function headings(): array
  {
    $sales_title = '當日銷售數量';
    if ($this->start_date && $this->end_date) {
      $sales_title = "銷售數量";
    }

    $headings = [
      "系統流水號",
      "商品編號",
      "主分類",
      "次分類",
      "商品名稱",
      "規格",
      "重量",
      "成本",
      "售價",
      "庫存",
      "儲位",
      $sales_title,
    ];
    return $headings;
  }

  public function map($model): array
  {
    $shop_classes_names  = [];
    $shop_subclass_names = [];
    foreach ($model->shop_classes as $shop_class) {
      $shop_classes_names[] = $shop_class->name;
    }
    foreach ($model->shop_subclasses as $shop_subclass) {
      $shop_subclass_names[] = $shop_subclass->name;
    }
    $shop_classes                = collect($shop_classes_names)->implode(',');
    $ahop_subclasses             = collect($shop_subclass_names)->implode(',');
    $weight                      = $model->weight_capacity . $model->weight_capacity;
    $shop_order_shop_product_arr = ShopOrderShopProduct::where('shop_product_id', $model->id)->whereDate('created_at', '<=', $this->end_date)->whereDate('created_at', '>=', $this->start_date)->get()->map(function ($item) {
      return $item->count;
    });
    $sales = ShopHelper::sum_total($shop_order_shop_product_arr);
    $map   = [
      $model->uuid,
      $model->no,
      $shop_classes,
      $ahop_subclasses,
      $model->name,
      $model->spec,
      $weight,
      $model->cost,
      $model->price,
      $model->stock_count,
      $model->storage_space,
      $sales,
    ];
    return $map;
  }
}
