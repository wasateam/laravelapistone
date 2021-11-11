<?php

namespace Wasateam\Laravelapistone\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserServicePlan_R1 extends JsonResource
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
      'created_at'   => $this->created_at,
      'service_plan' => new UserServicePlan_R0($this->service_plan),
    ];
    $res['user'] = new User_R0($this->user);
    return $res;
  }
}
