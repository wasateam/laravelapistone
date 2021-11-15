<?php

namespace Wasateam\Laravelapistone\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ServicePlan_R1 extends JsonResource
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
      'remark'        => $this->remark,
      'payload'       => $this->payload,
      'period_month'  => $this->period_month,
      'total_price'   => $this->total_price,
      'annual_price'  => $this->annual_price,
      'monthly_price' => $this->monthly_price,
    ];
  }
}
