<?php

namespace Wasateam\Laravelapistone\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceJob extends JsonResource
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
        'id'           => $this->id,
        'updated_at'   => $this->updated_at,
        'created_at'   => $this->created_at,
        'invoice_date' => $this->invoice_date,
        'status'       => $this->status,
        'shop_order'   => new ShopOrder_R2($this->shop_order),
      ];
    } else if (config('stone.mode') == 'webapi') {
      return [];
    }
  }
}
