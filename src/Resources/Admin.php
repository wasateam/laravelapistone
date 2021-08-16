<?php

namespace Wasateam\Laravelapistone\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Admin extends JsonResource
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
      'sequence'          => $this->sequence,
      'name'              => $this->name,
      'email'             => $this->email,
      'status'            => $this->status,
      'is_active'         => $this->is_active,
      'created_at'        => $this->created_at,
      'updated_at'        => $this->updated_at,
      'scopes'            => $this->scopes,
      'settings'          => $this->settings,
      'email_verified_at' => $this->email_verified_at,
      'updated_admin'     => new Admin_R1($this->updated_admin),
      'created_admin'     => new Admin_R1($this->created_admin),
    ];

    if (config('stone.auth.has_role')) {
      $res['roles'] = AdminRole_R1::collection($this->roles);
    }
    // if (config('stone.app_mode')) {
    //   $res['app_scopes'] = AdminAppScope_R1::collection($this->app_scopes);
    //   $res['app_roles']  = AppRole_R1::collection($this->app_roles);
    // }

    return $res;
  }
}
