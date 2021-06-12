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
      return [
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
    } else if (config('stone.mode') == 'webapi') {
      return [
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
    }
  }
}
