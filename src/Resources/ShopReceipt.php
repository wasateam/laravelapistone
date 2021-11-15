<?php

namespace Wasateam\Laravelapistone\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ShopReceipt extends JsonResource
{
  /**
   * Transform the resource into an array.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return array
   */
  public function toArray($request)
  {
    // $res = [
    //   'id'                       => $this->id,
    //   'no'                       => $this->no,
    //   'type'                     => $this->type,
    //   'receiver'                 => $this->receiver,
    //   'receiver_tel'             => $this->receiver_tel,
    //   'receiver_address'         => $this->receiver_address,
    //   'receive_remark'           => $this->receive_remark,
    //   'package_methods'          => $this->package_methods,
    //   'status'                   => $this->status,
    //   'deliver_way'              => $this->deliver_way,
    //   'delivery_time'            => $this->delivery_time,
    //   'delivery_remark'          => $this->delivery_remark,
    //   'customer_service_remark'  => $this->customer_service_remark,
    // ];
    // return $res;
  }
}
