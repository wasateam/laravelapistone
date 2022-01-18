<?php

namespace Wasateam\Laravelapistone\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ShopCartProduct_R1 extends JsonResource
{
  /**
   * Transform the resource into an array.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
   */
  public function toArray($request)
  {
    if (config('stone.mode') == 'cms') {
      $res = [
        'id'                => $this->id,
        'created_at'        => $this->created_at,
        'updated_at'        => $this->updated_at,
        'name'              => $this->name,
        'count'             => $this->count,
        'price'             => $this->price,
        'discount_price'    => $this->discount_price,
        'order_type'        => $this->order_type,
        'shop_product'      => new ShopProduct($this->shop_product),
        'shop_product_spec' => new ShopProductSpec($this->shop_product_spec),
      ];
    } else {
      $res = [
        'id'                => $this->id,
        'name'              => $this->name,
        'count'             => $this->count,
        'price'             => $this->price,
        'discount_price'    => $this->discount_price,
        'order_type'        => $this->order_type,
        'shop_product'      => new ShopProduct($this->shop_product),
        'shop_product_spec' => new ShopProductSpec($this->shop_product_spec),
      ];
    }
    return $res;
  }
}
