<?php

namespace Wasateam\Laravelapistone\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Wasateam\Laravelapistone\Models\ShopOrder;
use Carbon\Carbon;

class UserExport implements WithMapping, WithHeadings, FromCollection
{

  protected $shop_orders;

  public function __construct($shop_orders)
  {
    $this->shop_orders = $shop_orders;
  }

  /**
   * @return \Illuminate\Support\Collection
   */
  public function collection()
  {
    if ($this->shop_orders) {
      return ShopOrder::find($this->shop_orders);
    } else {
      return ShopOrder::all();
    }
  }

  public function headings(): array
  {
    $headings = [
      "訂單編號",
      "訂單類型",
      "訂購日期",
      "訂單狀態",
      "出貨狀態",
      "出貨日期",
      "收件者",
      "收件人電話",
      "收件人地址",
      "收件備註",
      "包裝方式",
      "物流方式",
      "客服備註",
      "建立時間",
      "最後更新時間",
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
