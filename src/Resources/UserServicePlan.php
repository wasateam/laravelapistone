<?php

namespace Wasateam\Laravelapistone\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserServicePlan extends JsonResource
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
      'id'         => $this->id,
      'created_at' => $this->created_at,
      'updated_at' => $this->updated_at,
    ];
    if (config('stone.mode') == 'cms') {
      $res['user']         = new User_R1($this->user);
      $res['service_plan'] = new ServicePlan_R1($this->service_plan);
    }
    return $res;
  }
}
