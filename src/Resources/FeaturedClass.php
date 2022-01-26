<?php

namespace Wasateam\Laravelapistone\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FeaturedClass extends JsonResource
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
      return [
        'id'         => $this->id,
        'updated_at' => $this->updated_at,
        'created_at' => $this->created_at,
        'name'       => $this->name,
        'icon'       => $this->icon,
        'sequence'   => $this->sequence,
        'order_type' => $this->order_type 
      ];
    } else if (config('stone.mode') == 'webapi') {
      return [
        'id'   => $this->id,
        'name' => $this->name,
        'icon' => $this->icon,
        'order_type' => $this->order_type 
      ];
    }
  }
}
