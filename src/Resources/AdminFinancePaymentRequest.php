<?php

namespace Wasateam\Laravelapistone\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AdminFinancePaymentRequest extends JsonResource
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
        'created_at'     => $this->created_at,
        'updated_at'     => $this->updated_at,
        'status'         => $this->status,
        'invoice_type'   => $this->invoice_type,
        'paying_type'    => $this->paying_type,
        'amount'         => $this->amount,
        'paying_date'    => $this->paying_date,
        'verify_date'    => $this->verify_date,
        'complete_date'  => $this->complete_date,
        'request_remark' => $this->request_remark,
        'payload'        => $this->payload,
        'review_remark'  => $this->review_remark,
        'admin'          => new Admin_R1($this->admin),
        'reviewer'       => new Admin_R1($this->reviewer),
      ];
    }
    return $res;
  }
}
