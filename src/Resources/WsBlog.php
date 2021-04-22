<?php

namespace Wasateam\Laravelapistone\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class WsBlog extends JsonResource
{
  /**
   * Transform the resource into an array.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return array
   */
  public function toArray($request)
  {
    return [
      'id'          => $this->id,
      'title'       => $this->title,
      'description' => $this->description,
      'publish_at'  => $this->publish_at,
      'read_count'  => $this->read_count,
      'content'     => $this->content,
      'tags'        => $this->tags,
      'cover_image' => new PocketImage_R1($this->cover_image),
    ];
  }
}
