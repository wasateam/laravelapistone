<?php

namespace Wasateam\Laravelapistone\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PageCover_R1 extends JsonResource
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
        'name'       => $this->name,
        'start_date' => $this->start_date,
        'end_date'   => $this->end_date,
        'is_active'  => $this->is_active,
        'link'       => $this->link,
        'image'      => $this->image,
      ];
    } else if (config('stone.mode') == 'webapi') {
      $res = [
        'id'    => $this->id,
        'name'  => $this->name,
        'link'  => $this->link,
        'image' => $this->image,
      ];
    }
    return $res;
  }
}
