<?php

namespace Wasateam\Laravelapistone\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Wasateam\Laravelapistone\Helpers\PocketHelper;

class NewsCollection extends JsonResource
{
  /**
   * Transform the resource collection into an array.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
   */
  public function toArray($request)
  {
    return [
      'id'            => $this->id,
      'title'         => $this->title,
      'description'   => $this->description,
      'publish_at'    => $this->publish_at,
      'read_count'    => $this->read_count,
      'rough_content' => $this->rough_content,
      'tags'          => $this->tags,
      'cover_image'   => PocketHelper::get_pocket_url($this->cover_image),
    ];
  }
}
