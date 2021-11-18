<?php

namespace Wasateam\Laravelapistone\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ShopCartProductCollection extends JsonResource
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
      'id'             => $this->id,
      'created_at'     => $this->created_at,
      'updated_at'     => $this->updated_at,
      'name'           => $this->name,
      'subtitle'       => $this->subtitle,
      'count'          => $this->count,
      'price'          => $this->price,
      'discount_price' => $this->discount_price,
      'count'          => $this->count,
      'shop_product'   => new ShopProduct_R1($this->shop_product),
    ];
  }
}
