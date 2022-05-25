<?php

namespace Wasateam\Laravelapistone\Modules\AppDeveloper\App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AppDeveloper extends JsonResource
{
  public function toArray($request)
  {
    if (config('stone.mode') == 'cms') {
      $res = [
        'id'                  => $this->id,
        'is_active'           => $this->is_active,
        'mobile'              => $this->mobile,
        'mobile_country_code' => $this->mobile_country_code,
        'otp'                 => $this->otp,
        'created_at'          => $this->created_at,
        'updated_at'          => $this->updated_at,
      ];
    }
    if (config('stone.mode') == 'webapi') {
    }
    return $res;
  }
}
