<?php

namespace Wasateam\Laravelapistone\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ShopReturnRecord extends JsonResource
{
  /**
   * Transform the resource into an array.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
   */
  public function toArray($request)
  {
    return [
      "id"                      => $this->id,
      "created_at"              => $this->created_at,
      "updated_at"              => $this->updated_at,
      "user"                    => new User_R1($this->user),
      "shop_order"              => new ShopOrder_R0($this->shop_order),
      "shop_order_shop_product" => new ShopOrderShopProduct($this->shop_order_shop_product),
      "shop_product"            => new ShopProduct_R0($this->shop_product),
      "count"                   => $this->count,
      "remark"                  => $this->remark,
      "return_reason"           => $this->return_reason,
      "type"                    => $this->type,
      "status"                  => $this->status,
      "price"                   => $this->price,
    ];
  }
}
