<?php

namespace Wasateam\Laravelapistone\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SystemClass_R1 extends JsonResource
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
        'updated_admin' => new Admin_R1($this->updated_admin),
        'sequence'      => $this->sequence,
        'updated_at'    => $this->updated_at,
        'name'          => $this->name,
      ];
    } else if (config('stone.mode') == 'webapi') {
      return [
        'id'   => $this->id,
        'name' => $this->name,
      ];
    }
  }
}
