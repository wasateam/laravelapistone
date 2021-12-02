<?php

namespace Wasateam\Laravelapistone\Exports;

use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Wasateam\Laravelapistone\Models\ShopProduct;

class ShopProductExport implements WithMapping, WithHeadings, FromCollection
{

  protected $shop_classes;
  protected $shop_subclasses;
  protected $is_active;

  public function __construct($shop_classes, $shop_subclasses, $is_active)
  {
    $this->shop_classes    = $shop_classes;
    $this->shop_subclasses = $shop_subclasses;
    $this->is_active       = $is_active;
  }

  /**
   * @return \Illuminate\Support\Collection
   */
  public function collection()
  {
    if ($this->shop_orders) {
      return ShopProduct::find($this->shop_orders);
    } else {
      return ShopProduct::all();
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
    $created_at = Carbon::parse($model->created_at)->format('Y-m-d');
    $updated_at = Carbon::parse($model->updated_at)->format('Y-m-d');
    $map        = [
      $model->no,
      $model->type,
      $model->status,
      $model->receiver,
      $model->receiver_tel,
      $model->receiver_address,
      $model->receive_remark,
      $model->package_methods,
      $model->deliver_way,
      $model->customer_service_remark,
      $created_at,
      $updated_at,
    ];
    return $map;
  }
}
