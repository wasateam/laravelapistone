<?php

namespace Wasateam\Laravelapistone\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Wasateam\Laravelapistone\Models\ShopProduct;

class ShopProductExport implements WithMapping, WithHeadings, FromCollection
{

  protected $shop_classes;
  protected $shop_subclasses;
  protected $is_active;
  protected $get_all;
  use Exportable;

  public function __construct($shop_classes, $shop_subclasses, $is_active, $get_all)
  {
    $this->shop_classes    = $shop_classes;
    $this->shop_subclasses = $shop_subclasses;
    $this->is_active       = $is_active;
    $this->get_all         = $get_all;
  }

  /**
   * @return \Illuminate\Support\Collection
   */
  public function collection()
  {
    if ($this->get_all || (!$this->is_active && !$this->shop_subclasses && !$this->shop_classes)) {
      return ShopProduct::all();
    } else {
      $is_active     = $this->is_active ? 1 : 0;
      $shop_products = ShopProduct::where('is_active', $is_active);
      if ($this->shop_classes) {
        $item_arr      = array_map('intval', explode(',', $this->shop_classes));
        $shop_products = $shop_products->with('shop_classes')->whereHas('shop_classes', function ($query) use ($item_arr) {
          return $query->whereIn('id', $item_arr);
        });
      }
      if ($this->shop_subclasses) {
        $item_arr      = array_map('intval', explode(',', $this->shop_subclasses));
        $shop_products = $shop_products->with('shop_subclasses')->whereHas('shop_subclasses', function ($query) use ($item_arr) {
          return $query->whereIn('id', $item_arr);
        });
      }
      return $shop_products->get();
    }
  }

  public function headings(): array
  {
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
      "當日銷售數量",
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
    $shop_classes    = collect($shop_classes_names)->implode(',');
    $ahop_subclasses = collect($shop_subclass_names)->implode(',');
    $weight          = $model->weight_capacity . $model->weight_capacity;
    $map             = [
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
      $model->today_shop_order_shop_products->count(),
    ];
    return $map;
  }
}
