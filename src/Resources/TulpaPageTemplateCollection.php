<?php

namespace Wasateam\Laravelapistone\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TulpaPageTemplateCollection extends JsonResource
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
        'created_at' => $this->created_at,
        'updated_at' => $this->updated_at,
        'name'       => $this->name,
        'tags'       => $this->tags,
        'remark'     => $this->remark,
        'content'    => $this->content,
      ];
    } else if (config('stone.mode') == 'webapi') {
      return [
        'id'      => $this->id,
        'name'    => $this->name,
        'tags'    => $this->tags,
        'remark'  => $this->remark,
        'content' => $this->content,
      ];
    }
  }
}
