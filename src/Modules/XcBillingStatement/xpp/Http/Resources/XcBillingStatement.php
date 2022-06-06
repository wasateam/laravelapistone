<?php

namespace Wasateam\Laravelapistone\Modules\XcBillingStatement\App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class XcBillingStatement extends JsonResource
{
  public function toArray($request)
  {
    if (config('stone.mode') == 'cms') {
      $res = [
        'id'            => $this->id,
        'invoice_type'  => $this->invoice_type,
        'images'        => $this->images,
        'pay_type'      => $this->pay_type,
        'amount'        => $this->amount,
        'pay_at'        => $this->pay_at,
        'review_at'     => $this->review_at,
        'remark'        => $this->remark,
        'review_status' => $this->review_status,
        'admin'         => new \Wasateam\Laravelapistone\Resources\Admin_R1($this->admin),
        'reviewer'      => new \Wasateam\Laravelapistone\Resources\Admin_R1($this->reviewer),
      ];
    }
    if (config('stone.mode') == 'webapi') {
    }
    return $res;
  }
}
