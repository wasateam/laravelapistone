<?php

namespace Wasateam\Laravelapistone\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ShopReturnRecordCollection extends ResourceCollection
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
      "shop_order_shop_product" => new ShopOrderShopProduct($this->shop_order_shop_product),
      "count"                   => $this->count,
      "remark"                  => $this->remark,
    ];
  }
}
