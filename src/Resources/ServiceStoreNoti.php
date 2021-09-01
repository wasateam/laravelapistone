<?php

namespace Wasateam\Laravelapistone\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ServiceStoreNoti extends JsonResource
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
      $res = [
        'id'             => $this->id,
        'updated_admin'  => new Admin_R1($this->updated_admin),
        'created_admin'  => new Admin_R1($this->created_admin),
        'updated_at'     => $this->updated_at,
        'content'        => $this->content,
        'start'          => $this->start,
        'end'            => $this->end,
        'service_stores' => ServiceStore_R1::collection($this->service_stores),
      ];
      if (config('stone.admin_group')) {
        $res['admin_groups'] = AdminGroup_R1::collection($this->admin_groups);
      }
      return $res;
    } else if (config('stone.mode') == 'webapi') {
      return [
        'id'             => $this->id,
        'content'        => $this->content,
        'start'          => $this->start,
        'end'            => $this->end,
        'service_stores' => ServiceStore_R1::collection($this->service_stores),
      ];
    }
  }
}
