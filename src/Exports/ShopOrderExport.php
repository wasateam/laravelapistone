<?php

namespace Wasateam\Laravelapistone\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Wasateam\Laravelapistone\Helpers\ShopHelper;
use Wasateam\Laravelapistone\Helpers\TimeHelper;
use Wasateam\Laravelapistone\Models\ShopOrder;

class ShopOrderExport implements FromArray, WithHeadings, ShouldAutoSize
{

  protected $shop_orders;
  protected $get_all;
  protected $country_code;

  public function __construct($shop_orders, $get_all, $country_code)
  {
    $this->shop_orders  = $shop_orders;
    $this->get_all      = $get_all;
    $this->country_code = $country_code;
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
      $shop_order_ids = array_map('intval', explode(',', $this->shop_orders));
      $shop_orders    = ShopOrder::whereIn('id', $shop_order_ids)->get();
    } else if ($this->get_all) {
      $shop_orders = ShopOrder::all();
    } else {
      $shop_orders = ShopOrder::all();
    }
    $array = [];
    foreach ($shop_orders as $shop_order) {
      $order_products = $shop_order->shop_order_shop_products;
      //area
      $area = $shop_order->area ? $shop_order->area->name : null;
      //area_section
      $area_section = $shop_order->area_section ? $shop_order->area_section->name : null;
      //ship_time
      $ship_time = $shop_order->ship_start_time . '-' . $shop_order->ship_end_time;
      //time_zone
      $timezone = TimeHelper::getTimeZoneFromCountryCode($this->country_code);
      //order_count
      $order_count = ShopHelper::getOrderCost($order_products);
      //order_original_count
      $order_original_count = ShopHelper::getOrderCost($order_products);
      $user                 = $shop_order->user;
      $first_purchase_check = 0;

      $current_year_paid_shop_orders = ShopOrder::where('user_id', $shop_order->user->id)
        ->whereYear('created_at', \Carbon\Carbon::parse($shop_order->created_at)->format('Y'))
        ->orderBy('pay_at', 'asc')
        ->get();
      if (count($current_year_paid_shop_orders) == 0) {
        $first_purchase_check = 1;
      } else if ($current_year_paid_shop_orders[0]->id == $shop_order->id) {
        $first_purchase_check = 1;
      }

      foreach ($order_products as $index => $order_product) {
        $shop_product = $order_product->shop_product;
        //product price
        $product_price = $order_product->discount_price && config('stone.shop.discount_price') ? $order_product->discount_price : $order_product->price;
        //return_count
        $return_count = ShopHelper::getProductReturnCount($order_product);
        if ($index == 0) {
          $array[] = [
            $first_purchase_check ? 'v' : null,
            $shop_order->user->id,
            $shop_order->orderer,
            $shop_order->orderer_tel,
            $shop_order->orderer_email,
            $shop_order->invoice_uniform_number,
            $shop_order->invoice_title,
            $shop_order->receiver,
            $shop_order->receiver_tel,
            $area,
            $area_section,
            $shop_order->receive_address,
            $shop_order->created_at->timezone($timezone),
            $shop_order->ship_date,
            $ship_time,
            $shop_order->order_type,
            $shop_order->ship_status,
            $shop_order->status,
            $shop_order->no,
            $shop_product->no,
            $order_product->name,
            $order_product->spec,
            $product_price,
            $order_product->cost,
            $order_product->original_count, //原始數量
            $product_price * $order_product->original_count, //售價小計
            $order_product->cost * $order_product->original_count, //原始成本小計
            100, //訂單原始金額
            $order_original_count, //訂單原始成本
            100, //訂單運費
            !$shop_order->freight ? 100 : 0, //免運折抵
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            null, //實收金額（原發票金額
            $return_count, //退刷數量
            $return_count * $order_product->price, //退刷售價小計
            $return_count * $order_product->cost, //退刷成本小計
            null, //訂單退刷金額
            null, //訂單退刷成本
            null, //退訂原因
            $shop_order->order_price, //退刷後訂單實收金額
            $shop_order->order_price, //重開發票金額
            $shop_order->order_price, //最終訂單實收金額
            $order_count, //最終訂單成本
          ];
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
            $product_price,
            $order_product->cost,
            $order_product->original_count, //原始數量
            $product_price * $order_product->original_count, //售價小計
            $order_product->cost * $order_product->original_count, //原始成本小計
            null, //訂單原始金額
            null, //訂單原始成本
            null, //訂單運費
            null, //免運折抵
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            null, //實收金額（原發票金額
            $return_count, //退刷數量
            $return_count * $order_product->price, //退刷售價小計
            $return_count * $order_product->cost, //退刷成本小計
            null, //訂單退刷金額
            null, //訂單退刷成本
            null, //退訂原因
            null, //退刷後訂單實收金額
            null, //重開發票金額
            null, //最終訂單實收金額
            null, //最終訂單成本
          ];
        }

      }
    }
    return $array;
  }
}
