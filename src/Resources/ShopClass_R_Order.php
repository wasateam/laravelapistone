<?php

namespace Wasateam\Laravelapistone\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ShopClass_R_Order extends JsonResource
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
      'id'              => $this->id,
      'name'            => $this->name,
      'sq'              => $this->sq,
      'shop_subclasses' => ShopSubclass_R_Order::collection($this->shop_subclasses),
    ];
    return $res;
  }
}
