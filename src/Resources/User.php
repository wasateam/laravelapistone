<?php

namespace Wasateam\Laravelapistone\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class User extends JsonResource
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
      'mama_language'     => $this->mama_language,
      'byebye_at'         => $this->byebye_at,
      'email_verified_at' => $this->email_verified_at,
      'birthday'          => $this->birthday,
      'is_active'         => $this->is_active,
      'uuid'              => $this->uuid,
      'pocket_avatar'     => new PocketImage_R1($this->pocket_avatar),
    ];
    if (config('stone.locale')) {
      $res['locale'] = new Locale_R1($this->locale);
    }
    if (config('stone.service_plan')) {
      $res['service_plans'] = ServicePlan_R1::collection($this->service_plans);
    }
    if (config('stone.user_device_token')) {
      $res['user_device_tokens'] = UserDeviceToken_R1::collection($this->user_device_tokens);
    }
    if (config('stone.user.is_bad')) {
      $res['is_bad'] = $this->is_bad;
    }
    if (config('stone.user.bonus_points')) {
      $res['bonus_points'] = $this->bonus_points;
    }
    return $res;
  }
}
