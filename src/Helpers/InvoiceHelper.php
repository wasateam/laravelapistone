<?php

namespace Wasateam\Laravelapistone\Helpers;

class InvoiceHelper
{
  // @Q@ 待調整完成
  // public static function createInvoice($shop_order)
  public static function createInvoice($invoice_type, $customer_email, $customer_tel, $customer_addr)
  {
    if (config('stone.invoice')) {
      if (config('stone.invoice.service') == 'ecpay') {
        try {
          $invoice_type   = $shop_order->invoice_type;
          $customer_email = $shop_order->orderer_email;
          $customer_tel   = $shop_order->orderer_tel;
          $customer_addr  = $shop_order->receive_address;
          $order_amount   = ShopHelper::getOrderAmount($_my_cart_products);
          $items          = EcpayHelper::getInvoiceItemsFromShopCartProducts($_my_cart_products);
          $customer_id    = Auth::user()->id;
          $post_data      = [
            'Items'         => $items,
            'SalesAmount'   => $order_amount,
            'TaxType'       => 1,
            'CustomerEmail' => $customer_email,
            'CustomerAddr'  => $customer_addr,
            'CustomerPhone' => $customer_tel,
            'CustomerID'    => $customer_id,
          ];
          if ($invoice_type == 'personal') {
            $invoice_carrier_type      = $shop_order->invoice_carrier_type;
            $invoice_carrier_number    = $shop_order->invoice_carrier_number;
            $post_data['Print']        = 0;
            $post_data['CustomerName'] = $shop_order->orderer;
            if ($invoice_carrier_type == 'mobile') {
              $post_data['CarrierType'] = 3;
              $post_data['CarrierNum']  = $invoice_carrier_number;
            } else if ($invoice_carrier_type == 'certificate') {
              $post_data['CarrierType'] = 2;
              $post_data['CarrierNum']  = $invoice_carrier_number;
            } else if ($invoice_carrier_type == 'email') {
              $post_data['CarrierType']   = 1;
              $post_data['CarrierNum']    = '';
              $post_data['CustomerEmail'] = $invoice_carrier_number;
            }
          } else if ($invoice_type == 'triple') {
            $invoice_title                   = $shop_order->invoice_title;
            $invoice_uniform_number          = $shop_order->invoice_uniform_number;
            $post_data['CarrierType']        = '';
            $post_data['Print']              = 1;
            $post_data['CustomerName']       = $invoice_title;
            $post_data['CustomerIdentifier'] = $invoice_uniform_number;
          }
          $post_data      = EcpayHelper::getInvoicePostData($post_data);
          $invoice_number = EcpayHelper::createInvoice($post_data);
          $invoice_status = 'done';
        } catch (\Throwable $th) {
          $invoice_status = 'fail';
        }
      }
    }
  }
}
