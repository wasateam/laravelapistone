<?php

namespace Wasateam\Laravelapistone\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ShopSubclass_R_Order_ShopProduct extends JsonResource
{
  /**
   * Transform the resource into an array.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return array
   */
  public function toArray($request)
  {
    $res = [
      'id'            => $this->id,
      'name'          => $this->name,
      'sq'            => $this->sq,
      'shop_products' => ShopProduct_R_Order_ShopSubclass::collection($this->shop_products),
    ];
    return $res;
  }
}
