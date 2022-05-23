<?php

namespace Wasateam\Laravelapistone\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class XcProject extends JsonResource
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
        'id'                => $this->id,
        'updated_at'        => $this->updated_at,
        'created_at'        => $this->created_at,
        'url'               => $this->url,
        'name'              => $this->name,
        'slack_webhook_url' => $this->slack_webhook_url,
        'status'            => $this->status,
        'slack_team'        => $this->slack_team,
        'slack_channel'     => $this->slack_channel,
        'zeplin'            => $this->zeplin,
        'gitlab'            => $this->gitlab,
        'github'            => $this->github,
        'google_drive'      => $this->google_drive,
        'remark'            => $this->remark,
        'links'             => $this->links,
        'infos'             => $this->infos,
        'payload'           => $this->payload,
      ];
    }
  }
}
