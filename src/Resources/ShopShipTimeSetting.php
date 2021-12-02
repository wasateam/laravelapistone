<?php

namespace Wasateam\Laravelapistone\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ShopShipTimeSetting extends JsonResource
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
        'id'                     => $this->id,
        'type'                   => $this->type,
        'start_time'             => $this->start_time,
        'end_time'               => $this->end_time,
        'max_count'              => $this->max_count,
        'today_shop_order_count' => $this->today_shop_orders->count(),
      ];
    } else if (config('stone.mode') == 'webapi') {
      $res = [
        'id'                     => $this->id,
        'type'                   => $this->type,
        'start_time'             => $this->start_time,
        'end_time'               => $this->end_time,
        'max_count'              => $this->max_count,
        'today_shop_order_count' => $this->today_shop_orders->count(),
      ];
    }
    return $res;
  }
}
