<?php

namespace Wasateam\Laravelapistone\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserServicePlanItem_R1 extends JsonResource
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
      'id'           => $this->id,
      'content'      => $this->content,
      'expired_at'   => $this->expired_at,
      'total_count'  => $this->total_count,
      'remain_count' => $this->remain_count,
    ];
    return $res;
  }
}
