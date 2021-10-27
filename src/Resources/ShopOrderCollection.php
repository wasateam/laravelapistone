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
    if(config('stone.mode') == 'cms') {
      $res = [
        'id'                       => $this->id,
        'no'                       => $this->no,
        'type'                     => $this->type,
        'selected'                 => $this->selected,
        'order_time'               => $this->order_time,
        'receiver'                 => $this->receiver,
        'receiver_tel'             => $this->receiver_tel,
        'receiver_address'         => $this->receiver_address,
        'receive_remark'           => $this->receive_remark,
        'package_methods'          => $this->package_methods,
        'order_status'             => $this->order_status,
        'order_remark_status'      => $this->order_remake_status,
        'logistics_methods'        => $this->logistics_methods,
        'delivery_time'            => $this->delivery_time,
        'delivery_remark'          => $this->delivery_remark,
        'shipment_status'          => $this->shipment_status,
        'shipment_date'            => $this->shipment_date,
        'customer_service'         => $this->customer_service,
      ];
      if (config('stone.shop.uuid')) {
        $res['uuid'] = $this->uuid;
      }
    } else if (config('stone.mode') == 'webapi') {
      $res = [
        'id'                       => $this->id,
        'no'                       => $this->no,
        'type'                     => $this->type,
        'selected'                 => $this->selected,
        'order_time'               => $this->order_time,
        'receiver'                 => $this->receiver,
        'receiver_tel'             => $this->receiver_tel,
        'receiver_address'         => $this->receiver_address,
        'receive_remark'           => $this->receive_remark,
        'package_methods'          => $this->package_methods,
        'order_status'             => $this->order_status,
        'order_remark_status'      => $this->order_remake_status,
        'logistics_methods'        => $this->logistics_methods,
        'delivery_time'            => $this->delivery_time,
        'delivery_remark'          => $this->delivery_remark,
        'shipment_status'          => $this->shipment_status,
        'shipment_date'            => $this->shipment_date,
        'customer_service'         => $this->customer_service,
      ];
      if (config('stone.shop.uuid')) {
        $res['uuid'] = $this->uuid;
      }
    }
    return $res;
  }
}
