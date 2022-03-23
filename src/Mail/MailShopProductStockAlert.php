<?php

namespace Wasateam\Laravelapistone\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Wasateam\Laravelapistone\Helpers\ShopHelper;

class MailShopProductStockAlert extends Mailable
{
  use Queueable, SerializesModels;

  /**
   * Create a new message instance.
   *
   * @return void
   */
  public function __construct($shop_product, $shop_product_spec)
  {
    $this->shop_product      = $shop_product;
    $this->shop_product_spec = $shop_product_spec;
  }

  /**
   * Build the message.
   *
   * @return $this
   */
  public function build()
  {
    if (config('stone.shop.stock_alert')) {
      if (config('stone.shop.stock_alert.contact_emails')) {
        $data = [
          'shop_product' => $this->shop_product,
        ];
        if ($this->shop_product_spec) {
          $data['shop_product_spec'] = $this->shop_product_spec;
          $data['spec_name']         = ShopHelper::getShopProductSpecName($this->shop_product_spec);
        }
        return $this->subject('商品庫存警示')->view('wasateam::wasa.mail.shop_stock_alert')->with($data);
      }
    }
  }
}
