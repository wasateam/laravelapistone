<?php

namespace Wasateam\Laravelapistone\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Wasateam\Laravelapistone\Helpers\ModelHelper;
use Wasateam\Laravelapistone\Helpers\ShopHelper;

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
    $shop_orders = ModelHelper::ws_IndexSnap($this->controller, $this->request)
      ->with('shop_order_shop_products')
      ->with('area')
      ->with('area_section')
      ->with('shop_order_shop_products.shop_product')
      ->with('shop_order_shop_products.shop_return_records')
      ->get();

    return ShopHelper::getShopOrderArrayForExport($shop_orders, $this->request);
  }
}
