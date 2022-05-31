<?php

namespace Wasateam\Laravelapistone\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AdminCollection extends JsonResource
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
      'id'    => $this->id,
      'name'  => $this->name,
      'email' => $this->email,
      'country_code'      => $this->country_code,
      'scopes'            => $this->scopes,
    ];

    if (config('stone.admin_role')) {
      $res['roles'] = AdminRole_R1::collection($this->roles);
    }

    if (config('stone.admin_group')) {
      if (config('stone.admin_blur')) {
        $res['cmser_groups'] = AdminGroup_R1::collection($this->cmser_groups);
      } else {
        $res['admin_groups'] = AdminGroup_R1::collection($this->admin_groups);
      }
    }

    return $res;
  }
}
