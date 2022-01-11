<?php

namespace Wasateam\Laravelapistone\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class XcTask extends JsonResource
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
        'xc_work_type'    => new XcWorkType_R1($this->xc_work_type),
        'xc_project'      => new XcProject_R1($this->xc_project),
        'creator'         => new Admin_R1($this->creator),
        'taker'           => new Admin_R1($this->taker),
      ];
    }
  }
}
