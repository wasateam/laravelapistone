<?php

namespace Wasateam\Laravelapistone\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ServicePlanItem extends JsonResource
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
      'name'       => $this->name,
      'type'       => $this->type,
      'unit'       => $this->unit,
      'items'      => $this->items,
      'uuid'       => $this->uuid,
    ];
  }
}
