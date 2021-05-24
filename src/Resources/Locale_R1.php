<?php

namespace Wasateam\Laravelapistone\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Locale_R1 extends JsonResource
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
      'id'   => $this->id,
      'code' => $this->code,
      'name' => $this->name,
    ];
  }
}
