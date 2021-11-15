<?php

namespace Wasateam\Laravelapistone\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ShopCart extends JsonResource
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
      'id'                 => $this->id,
      'created_at'         => $this->created_at,
      'updated_at'         => $this->updated_at,
      'user'               => new User_R1($this->user),
      'shop_cart_products' => ShopCartProduct_R1::collection($this->shop_cart_products),
    ];
  }
}
