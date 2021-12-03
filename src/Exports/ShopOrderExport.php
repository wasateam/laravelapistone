<?php

namespace Wasateam\Laravelapistone\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Wasateam\Laravelapistone\Models\ShopOrder;

class UserExport implements FromArray, WithHeadings, FromCollection, ShouldAutoSize
{

  protected $shop_orders;

  public function __construct($shop_orders)
  {
    $this->shop_orders = $shop_orders;
  }

  public function headings(): array
  {
    $headings = [
      "新客（當年度首次消費)",
      "會員編號",
      "訂購者",
      "訂購人電話",
      "訂購人信箱",
      "統一編號",
      "公司抬頭",
      "收件者",
      "收件人手機號碼",
      "縣市",
      "行政區",
      "地址",
      "訂購日期",
      "出貨日期",
      "配送時段",
      "訂單類型",
      "物流狀態",
      "訂單狀態",
      "訂單編號",
      "商品編號",
      "商品名稱",
      "商品規格",
      "售價",
      "成本",
      "數量",
      "售價小計",
      "成本小計",
      "訂單金額",
      "訂單成本",
      "運費",
      "免運折抵",
      "紅利折扣",
      "優惠券折扣",
      "優惠券活動",
      "折扣碼折扣",
      "折扣碼活動",
      "全館折扣",
      "全館折扣名稱",
      "實收金額（原發票金額",
      "退刷數量",
      "退刷售價小計",
      "退刷成本小計",
      "訂單退刷金額",
      "訂單退刷成本",
      "退訂原因",
      "退刷後訂單實收金額",
      "重開發票金額",
      "最終訂單實收金額",
      "最終訂單成本",
    ];
    return $headings;
  }

  function array(): array
  {
    $shop_orders = [];
    if ($this->shop_orders) {
      $shop_orders = ShopOrder::find($this->shop_orders);
    } else {
      $shop_orders = ShopOrder::all();
    }
    $array = [];
    foreach ($shop_orders as $shop_order) {
      $order_products = $shop_order->shop_order_shop_products;
      foreach ($order_products as $index => $order_product) {
        $shop_product = $order_product->shop_product;
        $price        = $order_product->discount_price ? $order_product->discount_price : $order_product->price;
        if ($index == 0) {
          $array[] = [];
        } else {
          $array[] = [
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            $shop_product->no,
            $order_product->name,
            $order_product->spec,
            $price,
            $order_product->cost,
            $order_product->count,
            $price * $order_product->count,
            $order_product->cost * $order_product->count,
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            null,
          ];
        }

      }
    }
    return $array;
  }
}
