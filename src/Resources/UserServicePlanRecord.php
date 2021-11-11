<?php

namespace Wasateam\Laravelapistone\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserServicePlanRecord extends JsonResource
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
      'count'      => $this->count,
      'remark'     => $this->remark,
    ];
    if (config('stone.mode') == 'cms') {
      $res['user']                   = new User_R1($this->user);
      $res['admin']                  = new Admin_R1($this->admin);
      $res['service_plan']           = new ServicePlan_R1($this->service_plan);
      $res['service_plan_item']      = new ServicePlanItem_R1($this->service_plan_item);
      $res['user_service_plan']      = new UserServicePlan_R1($this->user_service_plan);
      $res['user_service_plan_item'] = new UserServicePlanItem_R1($this->user_service_plan_item);
    }
    return $res;
  }
}
