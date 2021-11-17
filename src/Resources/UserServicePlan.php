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
      'id'                      => $this->id,
      'created_at'              => $this->created_at,
      'service_plan'            => new ServicePlan_R1($this->service_plan),
      'user_service_plan_items' => UserServicePlanItem_R2::collection($this->user_service_plan_items),
      'expired_at'              => $this->expired_at,
    ];
    if (config('stone.mode') == 'cms') {
      $res['user'] = new User_R1_CMS($this->user);
    }
    return $res;
  }
}
