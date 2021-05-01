<?php

namespace Wasateam\Laravelapistone\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserMy extends JsonResource
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
      'id'                => $this->id,
      'name'              => $this->name,
      'email'             => $this->email,
      'avatar'            => $this->avatar,
      'status'            => $this->status,
      'created_at'        => $this->created_at,
      'updated_at'        => $this->updated_at,
      'firestore_id'      => $this->firestore_id,
      'scopes'            => $this->scopes,
      'settings'          => $this->settings,
      'description'       => $this->description,
      'tel'               => $this->tel,
      'payload'           => $this->payload,
      'email_verified_at' => $this->email_verified_at,
      'pocket_avatar'     => new PocketImage_R1($this->pocket_avatar),
    ];
  }
}
