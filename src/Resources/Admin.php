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
    $value = [
      'id'                => $this->id,
      'name'              => $this->name,
      'email'             => $this->email,
      'status'            => $this->status,
      'created_at'        => $this->created_at,
      'updated_at'        => $this->updated_at,
      'scopes'            => $this->scopes,
      'settings'          => $this->settings,
      'email_verified_at' => $this->email_verified_at,
      'updated_admin'     => new Admin_R1($this->updated_admin),
    ];

    if (config('stone.auth.has_role')) {
      $value['roles'] = AdminRole_R1::collection($this->roles);
    }

    return $value;
  }
}
