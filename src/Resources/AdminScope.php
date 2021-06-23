<?php

namespace Wasateam\Laravelapistone\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AdminScope extends JsonResource
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
      'text'          => $this->text,
      'created_at'    => $this->created_at,
      'updated_at'    => $this->updated_at,
      'updated_admin' => new Admin_R1($this->updated_admin),
    ];
  }
}
