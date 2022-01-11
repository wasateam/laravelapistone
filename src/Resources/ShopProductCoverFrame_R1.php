<?php

namespace Wasateam\Laravelapistone\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ShopProductCoverFrame_R1 extends JsonResource
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
        'name'       => $this->name,
        'url'        => $this->url,
        'start_date' => $this->start_date,
        'end_date'   => $this->end_date,
        'ordre_type' => $this->order_type,
      ];
      if (config('stone.shop.uuid')) {
        $res['uuid'] = $this->uuid;
      }
    } else if (config('stone.mode') == 'webapi') {
      $res = [
        'uuid' => $this->uuid,
        'url'  => $this->url,
        'name' => $this->name,
      ];
    }
    return $res;
  }
}
