<?php

namespace Wasateam\Laravelapistone\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ShopFreeShipping extends JsonResource
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
      "id"         => $this->id,
      "name"       => $this->name,
      "price"      => $this->price,
      "start_date" => $this->start_date,
      "end_date"   => $this->end_date,
    ];
  }
}
