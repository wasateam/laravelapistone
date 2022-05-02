<?php

namespace Wasateam\Laravelapistone\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Wasateam\Laravelapistone\Helpers\ModelHelper;
use Wasateam\Laravelapistone\Helpers\ShopHelper;
use Wasateam\Laravelapistone\Helpers\TimeHelper;

class ShopOrderExport implements FromArray, WithHeadings, ShouldAutoSize, WithColumnWidths
{

  protected $controller;
  protected $request;

  public function __construct($controller, $request)
  {
    $this->controller = $controller;
    $this->request    = $request;
  }
  public function columnWidths(): array
  {
    return [
      'A'  => 5,
      'B'  => 20,
      'C'  => 15,
      'D'  => 15,
      'E'  => 25,
      'F'  => 10,
      'G'  => 20,
      'H'  => 10,
      'I'  => 15,
      'J'  => 10,
      'K'  => 10,
      'L'  => 30,
      'M'  => 15,
      'N'  => 15,
      'O'  => 20,
      'P'  => 10,
      'Q'  => 10,
      'R'  => 10,
      'S'  => 25,
      'T'  => 10,
      'U'  => 15,
      'V'  => 10,
      'W'  => 10,
      'X'  => 10,
      'Y'  => 10,
      'Z'  => 10,
      'AA' => 10,
      'AB' => 10,
      'AC' => 10,
      'AD' => 10,
      'AE' => 10,
      'AF' => 10,
      'AG' => 10,
      'AH' => 10,
      'AI' => 10,
      'AJ' => 10,
      'AK' => 10,
      'AL' => 10,
      'AM' => 10,
      'AN' => 10,
      'AO' => 15,
      'AP' => 15,
      'AQ' => 15,
      'AR' => 15,
      'AS' => 15,
      'AT' => 10,
      'AU' => 10,
      'AV' => 10,
      'AW' => 10,
    ];
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
      "紅利折抵",
      "優惠券折扣",
      "優惠券活動",
      "折扣碼折扣",
      "折扣碼活動",
      "邀請碼",
      "邀請碼折扣",
      "全館折扣",
      "全館折扣名稱",
      "實收金額（原發票金額",
      "備註",
      "支付方式",
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
    $shop_orders = ModelHelper::ws_IndexSnap($this->controller, $this->request)->get();

    $array = [];
    foreach ($shop_orders as $shop_order) {
      $order_products = $shop_order->shop_order_shop_products;
      $area           = $shop_order->area ? $shop_order->area->name : null;
      $area_section   = $shop_order->area_section ? $shop_order->area_section->name : null;
      $timezone       = 'UTC';
      if ($this->request && $this->request->country_code) {
        $timezone = TimeHelper::getTimeZoneFromCountryCode($this->request->country_code);
      } else if (config('stone.timezone')) {
        $timezone = config('stone.timezone');
      }
      $ship_time                          = ShopHelper::getShopOrderShipTimeRange($shop_order, $timezone);
      $order_original_cost                = ShopHelper::getOrderCost($order_products);
      $created_at                         = ShopHelper::getShopOrderCreatedAt($shop_order, $timezone);
      $ship_date                          = ShopHelper::getShopOrderShipDate($shop_order, $timezone);
      $first_purchase_check               = ShopHelper::checkFirstShopOrderOfYearOfUser($shop_order);
      $order_type                         = ShopHelper::getShopOrderOrderTypeTitle($shop_order);
      $ship_status                        = ShopHelper::getShopOrderShipStatusTitle($shop_order);
      $status                             = ShopHelper::getShopOrderStatusTitle($shop_order);
      $shop_campaign_discount_code_deduct = $shop_order->shop_campaign_discount_code_deduct;
      $shop_campaign_discount_code_name   = $shop_order->shop_campaign_discount_code ? $shop_order->shop_campaign_discount_code->name : null;

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
            $created_at,
            $ship_date,
            $ship_time,
            $order_type,
            $ship_status,
            $status,
            $shop_order->no,
            $shop_product->no,
            $order_product->name,
            $order_product->spec,
            $product_price,
            $order_product->cost,
            $order_product->original_count, //原始數量
            $product_price * $order_product->original_count, //售價小計
            $order_product->cost * $order_product->original_count, //原始成本小計
            $shop_order->order_price,
            $shop_order->order_cost_products, //訂單原始成本
            $shop_order->freight, //訂單運費
            $shop_order->freight_deduct, //免運折抵
            $shop_order->bonus_points_deduct,
            null,
            null,
            $shop_campaign_discount_code_deduct,
            $shop_campaign_discount_code_name,
            $shop_order->invite_no,
            $shop_order->invite_no_deduct,
            null,
            null, 
            $shop_order->order_price,
            $shop_order->customer_service_remark,
            $shop_order->pay_type_text,
            $return_count, //退刷數量
            $return_count * $order_product->price, //退刷售價小計
            $return_count * $order_product->cost, //退刷成本小計
            $shop_order->return_price, //訂單退刷金額
            $shop_order->return_cost, //訂單退刷成本
            $shop_order->return_reason, //退訂原因
            $shop_order->order_price_after_return, //退刷後訂單實收金額
            $shop_order->order_price_after_return, //重開發票金額
            $shop_order->order_price_after_return, //最終訂單實收金額
            $shop_order->order_cost, //最終訂單成本
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
