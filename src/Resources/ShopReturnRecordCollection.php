<?php

namespace Wasateam\Laravelapistone\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ShopReturnRecordCollection extends JsonResource
{
  /**
   * Transform the resource collection into an array.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
   */
  public function toArray($request)
  {
    return [
      "id"                      => $this->id,
      "created_at"              => $this->created_at,
      "shop_order"              => new ShopOrder_R0($this->shop_order),
      "shop_order_shop_product" => new ShopOrderShopProduct_R1($this->shop_order_shop_product),
      "count"                   => $this->count,
      "remark"                  => $this->remark,
      "return_reason"           => $this->return_reason,
      "type"                    => $this->type,
      "status"                  => $this->status,
      "price"                   => $this->price,
    ];
  }
}
