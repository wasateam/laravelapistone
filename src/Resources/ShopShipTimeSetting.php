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
        'id'         => $this->id,
        'start_time' => $this->start_time,
        'end_time'   => $this->end_time,
        'max_count'  => $this->max_count,
      ];
    } else if (config('stone.mode') == 'webapi') {
      $res = [
        'id'         => $this->id,
        'start_time' => $this->start_time,
        'end_time'   => $this->end_time,
        'max_count'  => $this->max_count,
      ];
    }
    return $res;
  }
}
