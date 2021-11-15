<?php

namespace Wasateam\Laravelapistone\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ShopCartCollection extends ResourceCollection
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
      'id'                 => $this->id,
      'user'               => new User_R1($this->updated_at),
      'shop_cart_products' => ShopCartProduct_R1::collection($this->shop_cart_products),
    ];
  }
}
