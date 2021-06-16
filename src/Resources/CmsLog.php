<?php

namespace Wasateam\Laravelapistone\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CmsLog extends JsonResource
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
      'id'         => $this->id,
      'created_at' => $this->created_at,
      'updated_at' => $this->updated_at,
      'type'       => $this->type,
      'payload'    => $this->payload,
      'admin'      => new Admin_R1($this->admin),
    ];
  }
}
