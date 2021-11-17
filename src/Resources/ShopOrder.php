<?php

namespace Wasateam\Laravelapistone\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ShopOrder extends JsonResource
{
  /**
   * Transform the resource into an array.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return array
   */
  public function toArray($request)
  {
    if (config('stone.mode') == 'cms') {
      $res = [
        'id'                       => $this->id,
        'no'                       => $this->no,
        'created_at'               => $this->created_at,
        'orderer'                  => $this->orderer,
        'orderer_tel'              => $this->orderer_tel,
        'orderer_birthday'         => $this->orderer_birthday,
        'orderer_email'            => $this->orderer_email,
        'orderer_gender'           => $this->orderer_gender,
        'receiver'                 => $this->receiver,
        'receiver_tel'             => $this->receiver_tel,
        'receiver_email'           => $this->receiver_email,
        'receiver_gender'          => $this->receiver_gender,
        'receiver_birthday'        => $this->receiver_birthday,
        'receive_address'          => $this->receive_address,
        'receive_remark'           => $this->receive_remark,
        'package_way'              => $this->package_way,
        'status'                   => $this->status,
        'status_remark'            => $this->status_remark,
        'ship_way'                 => $this->ship_way,
        'delivery_date'            => $this->delivery_date,
        'ship_start_time'          => $this->ship_start_time,
        'ship_end_time'            => $this->ship_end_time,
        'ship_remark'              => $this->ship_endship_remark_time,
        'ship_status'              => $this->ship_status,
        'ship_date'                => $this->ship_date,
        'customer_service_remark'  => $this->customer_service_remark,
        'receive_way'              => $this->receive_way,
        'pay_type'                 => $this->pay_type,
        'pay_status'               => $this->pay_status,
        'receive_way'              => $this->receive_way,
        'discounts'                => $this->discounts,
        'freight'                  => $this->freight,
        'products_price'           => $this->products_price,
        'order_price'              => $this->order_price,
        'invoice_number'           => $this->invoice_number,
        'invoice_status'           => $this->invoice_status,
        'invoice_type'             => $this->invoice_type,
        'invoice_carrier_number'   => $this->invoice_carrier_number,
        'invoice_tax_type'         => $this->invoice_tax_type,
        'invoice_title'            => $this->invoice_title,
        'invoice_company_name'     => $this->invoice_company_name,
        'invoice_address'          => $this->invoice_address,
        'shop_order_shop_products' => ShopOrderShopProductCollection::collection($this->shop_order_shop_products),
        'shop_return_records'      => ShopReturnRecord_R0::collection($this->shop_return_records),
      ];
      if (config('stone.shop.uuid')) {
        $res['uuid'] = $this->uuid;
      }
    } else if (config('stone.mode') == 'webapi') {
      $res = [
        'id'                       => $this->id,
        'no'                       => $this->no,
        'created_at'               => $this->created_at,
        'orderer'                  => $this->orderer,
        'orderer_tel'              => $this->orderer_tel,
        'orderer_birthday'         => $this->orderer_birthday,
        'orderer_email'            => $this->orderer_email,
        'orderer_gender'           => $this->orderer_gender,
        'receiver'                 => $this->receiver,
        'receiver_tel'             => $this->receiver_tel,
        'receiver_email'           => $this->receiver_email,
        'receiver_gender'          => $this->receiver_gender,
        'receiver_birthday'        => $this->receiver_birthday,
        'receive_address'          => $this->receive_address,
        'receive_remark'           => $this->receive_remark,
        'package_way'              => $this->package_way,
        'status'                   => $this->status,
        'status_remark'            => $this->status_remark,
        'ship_way'                 => $this->ship_way,
        'delivery_date'            => $this->delivery_date,
        'ship_start_time'          => $this->ship_start_time,
        'ship_end_time'            => $this->ship_end_time,
        'ship_remark'              => $this->ship_endship_remark_time,
        'ship_status'              => $this->ship_status,
        'ship_date'                => $this->ship_date,
        'customer_service_remark'  => $this->customer_service_remark,
        'receive_way'              => $this->receive_way,
        'pay_type'                 => $this->pay_type,
        'pay_status'               => $this->pay_status,
        'receive_way'              => $this->receive_way,
        'discounts'                => $this->discounts,
        'freight'                  => $this->freight,
        'products_price'           => $this->products_price,
        'order_price'              => $this->order_price,
        'invoice_number'           => $this->invoice_number,
        'invoice_status'           => $this->invoice_status,
        'invoice_type'             => $this->invoice_type,
        'invoice_carrier_number'   => $this->invoice_carrier_number,
        'invoice_tax_type'         => $this->invoice_tax_type,
        'invoice_title'            => $this->invoice_title,
        'invoice_company_name'     => $this->invoice_company_name,
        'invoice_address'          => $this->invoice_address,
        'shop_order_shop_products' => ShopOrderShopProductCollection::collection($this->shop_order_shop_products),
        'shop_return_records'      => ShopReturnRecord_R0::collection($this->shop_return_records),
      ];
      if (config('stone.shop.uuid')) {
        $res['uuid'] = $this->uuid;
      }
    }
    return $res;
  }
}
