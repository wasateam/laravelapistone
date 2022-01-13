<?php

namespace Wasateam\Laravelapistone\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ShopProductCoverFrame extends JsonResource
{
  /**
   * Transform the resource into an array.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return array
   */
  public function toArray($request)
  {
    if (config('stone.mode') == 'cms') {
      $res = [
        'id'         => $this->id,
        'updated_at' => $this->updated_at,
        'created_at' => $this->created_at,
        'name'       => $this->name,
        'url'        => $this->url,
        'start_date' => $this->start_date,
        'end_date'   => $this->end_date,
        'is_active'  => $this->is_active,
        'order_type' => $this->order_type,
      ];
      if (config('stone.shop.uuid')) {
        $res['uuid'] = $this->uuid;
      }
    } else if (config('stone.mode') == 'webapi') {
      $res = [
        'id'         => $this->id,
        'name'       => $this->name,
        'url'        => $this->url,
        'start_date' => $this->start_date,
        'end_date'   => $this->end_date,
        'order_type' => $this->order_type,
      ];
      if (config('stone.shop.uuid')) {
        $res['uuid'] = $this->uuid;
      }
    }
    return $res;
  }
}
