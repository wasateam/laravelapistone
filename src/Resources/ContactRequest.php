<?php

namespace Wasateam\Laravelapistone\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ContactRequest extends JsonResource
{
  /**
   * Transform the resource into an array.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return array
   */
  public function toArray($request)
  {
    if (config('stone.mode') == 'cms') {
      $res = [
        'id'            => $this->id,
        'created_at'    => $this->created_at,
        'updated_at'    => $this->updated_at,
        'updated_admin' => new Admin_R1($this->updated_admin),
        'name'          => $this->name,
        'email'         => $this->email,
        'tel'           => $this->tel,
        'remark'        => $this->remark,
        'company_name'  => $this->company_name,
        'budget'        => $this->budget,
        'payload'       => $this->payload,
        'ip'            => $this->ip,
      ];
      if (config('stone.country_code')) {
        $res['country_code'] = $this->country_code;
      }
    } else if (config('stone.mode') == 'webapi') {
      $res = [
        'id'           => $this->id,
        'created_at'   => $this->created_at,
        'updated_at'   => $this->updated_at,
        'name'         => $this->name,
        'email'        => $this->email,
        'tel'          => $this->tel,
        'remark'       => $this->remark,
        'company_name' => $this->company_name,
        'budget'       => $this->budget,
        'payload'      => $this->payload,
      ];
      if (config('stone.country_code')) {
        $res['country_code'] = $this->country_code;
      }
    }
    return $res;
  }
}
