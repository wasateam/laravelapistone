<?php

namespace Wasateam\Laravelapistone\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BonusPointRecord extends JsonResource
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
      return [
        'id'            => $this->id,
        'created_at'    => $this->created_at,
        'updated_at'    => $this->updated_at,
        'type'          => $this->type,
        'source'        => $this->source,
        'count'         => $this->count,
        'user'          => new User_R1($this->user),
        'shop_order'    => new ShopOrder_R2($this->shop_order),
        'shop_campaign' => new User_R1($this->shop_campaign),
      ];
    } else if (config('stone.mode') == 'webapi') {
      return [
        'id'            => $this->id,
        'created_at'    => $this->created_at,
        'updated_at'    => $this->updated_at,
        'type'          => $this->type,
        'source'        => $this->source,
        'count'         => $this->count,
        'user'          => new User_R1($this->user),
        'shop_order'    => new ShopOrder_R2($this->shop_order),
        'shop_campaign' => new User_R1($this->shop_campaign),
      ];
    }
  }
}
