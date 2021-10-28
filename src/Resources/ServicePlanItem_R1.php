<?php

namespace Wasateam\Laravelapistone\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ServicePlanItem_R1 extends JsonResource
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
      'id'    => $this->id,
      'name'  => $this->name,
      'type'  => $this->type,
      'unit'  => $this->unit,
      'items' => $this->items,
      'uuid'  => $this->uuid,
    ];
  }
}
