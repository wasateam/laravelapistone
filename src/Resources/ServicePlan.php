<?php

namespace Wasateam\Laravelapistone\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ServicePlan extends JsonResource
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
      'created_at'    => $this->created_at,
      'updated_at'    => $this->updated_at,
      'name'          => $this->name,
      'code'          => $this->code,
      'remark'        => $this->remark,
      'payload'       => $this->payload,
      'period_month'  => $this->period_month,
      'total_price'   => $this->total_price,
      'annual_price'  => $this->annual_price,
      'monthly_price' => $this->monthly_price,
    ];
  }
}
