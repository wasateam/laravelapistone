<?php

namespace Wasateam\Laravelapistone\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TulpaCrossItem_R1 extends JsonResource
{
  /**
   * Transform the resource into an array.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return array
   */
  public function toArray($request)
  {
    $res = [
      'id'            => $this->id,
      'name'          => $this->name,
      'position'      => $this->position,
      'content'       => $this->content,
      'tulpa_section' => new TulpaSection_R1($this->tulpa_section),
    ];
    return $res;
  }
}
