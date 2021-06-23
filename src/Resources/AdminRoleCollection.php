<?php

namespace Wasateam\Laravelapistone\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AdminRoleCollection extends JsonResource
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
      'name'       => $this->name,
      'is_default' => $this->is_default,
      'scopes'     => $this->scopes,
    ];
  }
}
