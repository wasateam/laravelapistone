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
    return [
      'id'            => $this->id,
      'name'          => $this->name,
      'email'         => $this->email,
      'avatar'        => $this->avatar,
      'status'        => $this->status,
      'created_at'    => $this->created_at,
      'updated_at'    => $this->updated_at,
      'firestore_id'  => $this->firestore_id,
      'scopes'        => $this->scopes,
      'settings'      => $this->settings,
      'tel'           => $this->tel,
      'payload'       => $this->payload,
      'updated_admin' => new Admin_R1($this->updated_admin),
    ];
  }
}
