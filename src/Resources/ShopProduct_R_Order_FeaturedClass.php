<?php

namespace Wasateam\Laravelapistone\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ShopProduct_R_Order_FeaturedClass extends JsonResource
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
      'id'          => $this->id,
      'name'        => $this->name,
      'no'          => $this->no,
      'spec'        => $this->spec,
      'cost'        => $this->cost,
      'price'       => $this->price,
      'stock_count' => $this->stock_count,
      'cover_image' => $this->cover_image,
    ];
    return $res;
  }
}
