<?php

namespace Wasateam\Laravelapistone\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Showcase extends JsonResource
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
        'id'          => $this->id,
        'created_at'  => $this->created_at,
        'updated_at'  => $this->updated_at,
        'sq'          => $this->sq,
        'name'        => $this->name,
        'description' => $this->description,
        'color'       => $this->color,
        'route_name'  => $this->route_name,
        'tags'        => $this->tags,
        'is_active'   => $this->is_active,
        'content'     => $this->content,
      ];
    } else if (config('stone.mode') == 'webapi') {
      return [
        'id'          => $this->id,
        'created_at'  => $this->created_at,
        'updated_at'  => $this->updated_at,
        'sq'          => $this->sq,
        'name'        => $this->name,
        'description' => $this->description,
        'color'       => $this->color,
        'route_name'  => $this->route_name,
        'tags'        => $this->tags,
        'is_active'   => $this->is_active,
        'content'     => $this->content,
      ];
    }
  }
}
