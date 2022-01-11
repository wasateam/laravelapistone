<?php

namespace Wasateam\Laravelapistone\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class XcTask_R1 extends JsonResource
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
        'name'            => $this->name,
        'start_at'        => $this->start_at,
        'reviewed_at'     => $this->reviewed_at,
        'status'          => $this->status,
        'open'            => $this->open,
        'done'            => $this->done,
        'closed'          => $this->closed,
        'hour'            => $this->hour,
        'finish_hour'     => $this->finish_hour,
        'creator_remark'  => $this->creator_remark,
        'taker_remark'    => $this->taker_remark,
        'is_adjust'       => $this->is_adjust,
        'is_rd'           => $this->is_rd,
        'is_not_complete' => $this->is_not_complete,
      ];
    }
  }
}
