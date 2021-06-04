<?php

namespace Wasateam\Laravelapistone\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Locale extends JsonResource
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
        'id'            => $this->id,
        'updated_admin' => new Admin($this->updated_admin),
        'created_admin' => new Admin($this->created_admin),
        'sequence'      => $this->sequence,
        'updated_at'    => $this->updated_at,
        'created_at'    => $this->created_at,
        'code'          => $this->code,
        'name'          => $this->name,
        'backup_locale' => new Locale_R1($this->backup_locale),
      ];
    } else if (config('stone.mode') == 'webapi') {
      return [
        'id'            => $this->id,
        'sequence'      => $this->sequence,
        'updated_at'    => $this->updated_at,
        'created_at'    => $this->created_at,
        'code'          => $this->code,
        'name'          => $this->name,
        'backup_locale' => new Locale_R1($this->backup_locale),
      ];
    }
  }
}
