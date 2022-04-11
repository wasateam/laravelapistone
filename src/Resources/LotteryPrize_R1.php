<?php

namespace Wasateam\Laravelapistone\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LotteryPrize_R1 extends JsonResource
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
      $res = [
        'id'         => $this->id,
        'updated_at' => $this->updated_at,
        'created_at' => $this->created_at,
        'name'       => $this->name,
        'uuid'       => $this->uuid,
        'count'      => $this->count,
      ];
    } else if (config('stone.mode') == 'webapi') {
      $res = [
        'id'         => $this->id,
        'updated_at' => $this->updated_at,
        'created_at' => $this->created_at,
        'name'       => $this->name,
        'uuid'       => $this->uuid,
        'count'      => $this->count,
      ];
    }
    return $res;
  }
}
