<?php

namespace Wasateam\Laravelapistone\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class XcTaskTemplate extends JsonResource
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
        'id'              => $this->id,
        'updated_at'      => $this->updated_at,
        'created_at'      => $this->created_at,
        'name'            => $this->name,
        'hour'            => $this->hour,
        'is_adjust'       => $this->is_adjust,
        'is_rd'           => $this->is_rd,
        'is_not_complete' => $this->is_not_complete,
        'remark'          => $this->remark,
        'xc_work_type'    => new XcWorkType_R1($this->xc_work_type),
      ];
    }
  }
}
