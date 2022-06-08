<?php

namespace Wasateam\Laravelapistone\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ShopOrderShopProduct_R2 extends JsonResource
{
  /**
   * Transform the resource collection into an array.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
   */
  public function toArray($request)
  {
    if (config('stone.mode') == 'cms') {
      $res = [
        'id'                   => $this->id,
        'no'                   => $this->no,
        'type'                 => $this->type,
        'created_at'           => $this->created_at,
        'updated_at'           => $this->updated_at,
        'name'                 => $this->name,
        'subtitle'             => $this->subtitle,
        'price'                => $this->price,
        'spec'                 => $this->spec,
        'weight_capacity'      => $this->weight_capacity,
        'weight_capacity_unit' => $this->weight_capacity_unit,
        'cover_image'          => $this->cover_image,
        'count'                => $this->count,
        'original_count'       => $this->original_count,
        'cost'                 => $this->cost,
        'order_type'           => $this->order_type,
        'freight'              => $this->freight,
        'storage_space'        => $this->storage_space,
        'shop_product'         => new ShopProduct_R4($this->shop_product),
      ];
      if (config('stone.shop.discount_price')) {
        $res['discount_price'] = $this->discount_price;
      }
    }
    return $res;
  }
}
