<?php

namespace Wasateam\Laravelapistone\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ShopCampaignCollection extends JsonResource
{
  /**
   * Transform the resource collection into an array.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
   */
  public function toArray($request)
  {
    return [
      "id"         => $this->id,
      "name"       => $this->name,
      "type"       => $this->type,
      "start_date" => $this->start_date,
      "end_date"   => $this->end_date,
      "condition"  => $this->condition,
      "is_active"  => $this->is_active,
    ];
  }
}
