<?php

namespace Wasateam\Laravelapistone\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PinCard extends JsonResource
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
      'id'           => $this->id,
      'created_at'   => $this->created_at,
      'updated_at'   => $this->updated_at,
      'pin'          => $this->pin,
      'service_plan' => new ServicePlan_R1($this->service_plan),
    ];
  }
}
