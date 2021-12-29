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
    $res = [
      'id'                 => $this->id,
      'uuid'               => $this->uuid,
      'name'               => $this->name,
      'email'              => $this->email,
      'avatar'             => $this->avatar,
      'status'             => $this->status,
      'created_at'         => $this->created_at,
      'updated_at'         => $this->updated_at,
      'firestore_id'       => $this->firestore_id,
      'scopes'             => $this->scopes,
      'settings'           => $this->settings,
      'description'        => $this->description,
      'tel'                => $this->tel,
      'payload'            => $this->payload,
      'mama_language'      => $this->mama_language,
      'byebye_at'          => $this->byebye_at,
      'email_verified_at'  => $this->email_verified_at,
      'birthday'           => $this->birthday,
      'gender'             => $this->gender,
      'subscribe_start_at' => $this->subscribe_start_at,
      'subscribe_end_at'   => $this->subscribe_end_at,
      'color'              => $this->color,
      'pocket_avatar'      => new PocketImage_R1($this->pocket_avatar),
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
    if (config('stone.user.address')) {
      $res['addresses'] = UserAddress::collection($this->addresses);
    }
    if (config('stone.user.carriers')) {
      $res['carrier_email']       = $this->carrier_email;
      $res['carrier_phone']       = $this->carrier_phone;
      $res['carrier_certificate'] = $this->carrier_certificate;
    }
    if (config('stone.user.acumatica_id')) {
      $res['acumatica_id'] = $this->acumatica_id;
    }
    if (config('stone.user.customer_id')) {
      $res['customer_id'] = $this->customer_id;
    }
    return $res;
  }
}
