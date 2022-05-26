<?php

namespace Wasateam\Laravelapistone\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Wasateam\Laravelapistone\Models\ShopOrder;

class ShopOrderShipped extends Mailable
{
  use Queueable, SerializesModels;
  public $shop_order_id;

  /**
   * Create a new message instance.
   *
   * @return void
   */
  public function __construct($shop_order_id)
  {
    $this->shop_order_id = $shop_order_id;
  }

  /**
   * Build the message.
   *
   * @return $this
   */
  public function build()
  {
    $shop_order = ShopOrder::find($this->shop_order_id);
    $order_link = config('stone.web_url') . '/user/order/' . $shop_order->id;
    $subject    = '【出貨通知】' . config('app.name') . '會員您好，您的訂單：' . $shop_order->no;
    return $this->subject($subject)->view('wasateam::wasa.mail.shop_order_shipped')->with([
      'shop_order' => $shop_order,
      'order_link' => $order_link,
    ]);
  }
}
