<?php

namespace Wasateam\Laravelapistone\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class NewsBanner_R_Order extends JsonResource
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
      'id'   => $this->id,
      'name' => $this->name,
      'sq'   => $this->sq,
    ];
    return $res;
  }
}
