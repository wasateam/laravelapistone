<?php

namespace Wasateam\Laravelapistone\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PageCover extends JsonResource
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
        'id'            => $this->id,
        'updated_at'    => $this->updated_at,
        'created_at'    => $this->created_at,
        'name'          => $this->name,
        'start_date'    => $this->start_date,
        'end_date'      => $this->end_date,
        'is_active'     => $this->is_active,
        'link'          => $this->link,
        'image'         => $this->image,
        'page_settings' => PageSetting_R1::collection($this->page_settings),
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
