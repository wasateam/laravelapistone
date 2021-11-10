<?php

namespace Wasateam\Laravelapistone\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ShopOrderShopProductCollection extends ResourceCollection
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
      'id'                => $this->id,
      'created_at'        => $this->created_at,
      'updated_at'        => $this->updated_at,
      'name'              => $this->name,
      'subtitle'          => $this->subtitle,
      'price'             => $this->price,
      'discount_price'    => $this->discount_price,
      'spec'              => $this->spec,
      'weight_capacity'   => $this->weight_capacity,
      'cover_image'       => $this->cover_image,
      'count'             => $this->count,
      'discount_price'    => $this->discount_price,
      'shop_order'        => new ShopOrder_R0($this->shop_order),
      'shop_product'      => new ShopProduct_R0($this->shop_product),
      'shop_cart_product' => new ShopCartProduct_R1($this->shop_cart_product),
    ];
  }
}
