<?php

namespace Wasateam\Laravelapistone\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ShopProductImportRecord extends JsonResource
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
      "id"            => $this->id,
      "created_at"    => $this->created_at,
      "updated_at"    => $this->updated_at,
      "no"            => $this->no,
      "storage_space" => $this->storage_space,
      "stock_count"   => $this->stock_count,
      "uuid"          => $this->uuid,
      'shop_product'  => new ShopProduct_R1($this->shop_product),
    ];
  }
}
