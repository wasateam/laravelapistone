<?php

namespace Wasateam\Laravelapistone\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserServicePlanItem_R2 extends JsonResource
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
      'id'                => $this->id,
      'created_at'        => $this->created_at,
      'updated_at'        => $this->updated_at,
      'content'           => $this->content,
      'expired_at'        => $this->expired_at,
      'total_count'       => $this->total_count,
      'remain_count'      => $this->remain_count,
      'service_plan_item' => new ServicePlanItem_R1($this->service_plan_item),
    ];
    if (config('stone.mode') == 'cms') {
      $res['user'] = new User_R1($this->user);
    }
    return $res;
  }
}
