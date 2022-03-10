<?php

namespace Wasateam\Laravelapistone\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ShopOrder extends JsonResource
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
        'id'                        => $this->id,
        'no'                        => $this->no,
        'type'                      => $this->type,
        'order_type'                => $this->order_type,
        'created_at'                => $this->created_at,
        'orderer'                   => $this->orderer,
        'orderer_tel'               => $this->orderer_tel,
        'orderer_birthday'          => $this->orderer_birthday,
        'orderer_email'             => $this->orderer_email,
        'orderer_gender'            => $this->orderer_gender,
        'receiver'                  => $this->receiver,
        'receiver_tel'              => $this->receiver_tel,
        'receiver_email'            => $this->receiver_email,
        'receiver_gender'           => $this->receiver_gender,
        'receiver_birthday'         => $this->receiver_birthday,
        'receive_address'           => $this->receive_address,
        'receive_remark'            => $this->receive_remark,
        'package_way'               => $this->package_way,
        'status'                    => $this->status,
        'status_remark'             => $this->status_remark,
        'ship_way'                  => $this->ship_way,
        'ship_start_time'           => $this->ship_start_time,
        'ship_end_time'             => $this->ship_end_time,
        'ship_remark'               => $this->ship_remark,
        'ship_status'               => $this->ship_status,
        'ship_date'                 => $this->ship_date,
        'customer_service_remark'   => $this->customer_service_remark,
        'receive_way'               => $this->receive_way,
        'pay_type'                  => $this->pay_type,
        'pay_status'                => $this->pay_status,
        'receive_way'               => $this->receive_way,
        'discounts'                 => $this->discounts,
        'freight'                   => $this->freight,
        'products_price'            => $this->products_price,
        'order_price'               => $this->order_price,
        'invoice_number'            => $this->invoice_number,
        'invoice_status'            => $this->invoice_status,
        'invoice_type'              => $this->invoice_type,
        'reinvoice_at'              => $this->reinvoice_at,
        'invoice_carrier_number'    => $this->invoice_carrier_number,
        'invoice_tax_type'          => $this->invoice_tax_type,
        'invoice_title'             => $this->invoice_title,
        'invoice_company_name'      => $this->invoice_company_name,
        'invoice_address'           => $this->invoice_address,
        'invoice_uniform_number'    => $this->invoice_uniform_number,
        'invoice_carrier_type'      => $this->invoice_carrier_type,
        'invoice_email'             => $this->invoice_email,
        'shop_order_shop_products'  => ShopOrderShopProduct_R1::collection($this->shop_order_shop_products),
        'shop_return_records'       => ShopReturnRecord_R0::collection($this->shop_return_records),
        'ecpay_merchant_id'         => $this->ecpay_merchant_id,
        'ecpay_trade_no'            => $this->ecpay_trade_no,
        'ecpay_charge_fee'          => $this->ecpay_charge_fee,
        'pay_at'                    => $this->pay_at,
        'csv_pay_from'              => $this->csv_pay_from,
        'csv_payment_no'            => $this->csv_payment_no,
        'csv_payment_url'           => $this->csv_payment_url,
        'barcode_pay_from'          => $this->barcode_pay_from,
        'atm_acc_bank'              => $this->atm_acc_bank,
        'atm_acc_no'                => $this->atm_acc_no,
        'card_auth_code'            => $this->card_auth_code,
        'card_gwsr'                 => $this->card_gwsr,
        'card_process_at'           => $this->card_process_at,
        'card_amount'               => $this->card_amount,
        'card_pre_six_no'           => $this->card_pre_six_no,
        'card_last_four_no'         => $this->card_last_four_no,
        'card_stage'                => $this->card_stage,
        'card_stast'                => $this->card_stast,
        'card_staed'                => $this->card_staed,
        'card_red_dan'              => $this->card_red_dan,
        'card_red_de_amt'           => $this->card_red_de_amt,
        'card_red_ok_amt'           => $this->card_red_ok_amt,
        'card_red_yet'              => $this->card_red_yet,
        'card_period_type'          => $this->card_period_type,
        'card_frequency'            => $this->card_frequency,
        'card_exec_times'           => $this->card_exec_times,
        'card_period_amount'        => $this->card_period_amount,
        'card_total_success_times'  => $this->card_total_success_times,
        'card_total_success_amount' => $this->card_total_success_amount,
        'bonus_points_deduct'       => $this->bonus_points_deduct,
        'campaign_deduct'           => $this->campaign_deduct,
        'shop_campaign_shop_orders' => ShopCampaignShopOrder::collection($this->shop_campaign_shop_orders),
      ];
      if (config('stone.shop.uuid')) {
        $res['uuid'] = $this->uuid;
      }
      if (config('stone.the_point')) {
        $res['the_point_records'] = ThePointRecord_R1::collection($this->the_point_records);
      }
    } else if (config('stone.mode') == 'webapi') {
      $res = [
        'id'                        => $this->id,
        'no'                        => $this->no,
        'type'                      => $this->type,
        'order_type'                => $this->order_type,
        'created_at'                => $this->created_at,
        'orderer'                   => $this->orderer,
        'orderer_tel'               => $this->orderer_tel,
        'orderer_birthday'          => $this->orderer_birthday,
        'orderer_email'             => $this->orderer_email,
        'orderer_gender'            => $this->orderer_gender,
        'receiver'                  => $this->receiver,
        'receiver_tel'              => $this->receiver_tel,
        'receiver_email'            => $this->receiver_email,
        'receiver_gender'           => $this->receiver_gender,
        'receiver_birthday'         => $this->receiver_birthday,
        'receive_address'           => $this->receive_address,
        'receive_remark'            => $this->receive_remark,
        'package_way'               => $this->package_way,
        'status'                    => $this->status,
        'status_remark'             => $this->status_remark,
        'ship_way'                  => $this->ship_way,
        'ship_start_time'           => $this->ship_start_time,
        'ship_end_time'             => $this->ship_end_time,
        'ship_remark'               => $this->ship_remark,
        'ship_status'               => $this->ship_status,
        'ship_date'                 => $this->ship_date,
        'customer_service_remark'   => $this->customer_service_remark,
        'receive_way'               => $this->receive_way,
        'pay_type'                  => $this->pay_type,
        'pay_status'                => $this->pay_status,
        'receive_way'               => $this->receive_way,
        'discounts'                 => $this->discounts,
        'freight'                   => $this->freight,
        'products_price'            => $this->products_price,
        'order_price'               => $this->order_price,
        'invoice_number'            => $this->invoice_number,
        'invoice_status'            => $this->invoice_status,
        'invoice_type'              => $this->invoice_type,
        'invoice_carrier_number'    => $this->invoice_carrier_number,
        'invoice_tax_type'          => $this->invoice_tax_type,
        'invoice_title'             => $this->invoice_title,
        'invoice_company_name'      => $this->invoice_company_name,
        'invoice_address'           => $this->invoice_address,
        'invoice_uniform_number'    => $this->invoice_uniform_number,
        'invoice_carrier_type'      => $this->invoice_carrier_type,
        'invoice_email'             => $this->invoice_email,
        'shop_order_shop_products'  => ShopOrderShopProduct_R1::collection($this->shop_order_shop_products),
        'shop_return_records'       => ShopReturnRecord_R0::collection($this->shop_return_records),
        'ecpay_merchant_id'         => $this->ecpay_merchant_id,
        'ecpay_trade_no'            => $this->ecpay_trade_no,
        'ecpay_charge_fee'          => $this->ecpay_charge_fee,
        'pay_at'                    => $this->pay_at,
        'csv_pay_from'              => $this->csv_pay_from,
        'csv_payment_no'            => $this->csv_payment_no,
        'csv_payment_url'           => $this->csv_payment_url,
        'barcode_pay_from'          => $this->barcode_pay_from,
        'atm_acc_bank'              => $this->atm_acc_bank,
        'atm_acc_no'                => $this->atm_acc_no,
        'card_auth_code'            => $this->card_auth_code,
        'card_gwsr'                 => $this->card_gwsr,
        'card_process_at'           => $this->card_process_at,
        'card_amount'               => $this->card_amount,
        'card_pre_six_no'           => $this->card_pre_six_no,
        'card_last_four_no'         => $this->card_last_four_no,
        'card_stage'                => $this->card_stage,
        'card_stast'                => $this->card_stast,
        'card_staed'                => $this->card_staed,
        'card_red_dan'              => $this->card_red_dan,
        'card_red_de_amt'           => $this->card_red_de_amt,
        'card_red_ok_amt'           => $this->card_red_ok_amt,
        'card_red_yet'              => $this->card_red_yet,
        'card_period_type'          => $this->card_period_type,
        'card_frequency'            => $this->card_frequency,
        'card_exec_times'           => $this->card_exec_times,
        'card_period_amount'        => $this->card_period_amount,
        'card_total_success_times'  => $this->card_total_success_times,
        'card_total_success_amount' => $this->card_total_success_amount,
        'bonus_points_deduct'       => $this->bonus_points_deduct,
        'campaign_deduct'           => $this->campaign_deduct,
        'shop_campaign_shop_orders' => ShopCampaignShopOrder::collection($this->shop_campaign_shop_orders),
      ];
      if (config('stone.shop.uuid')) {
        $res['uuid'] = $this->uuid;
      }
      if (config('stone.the_point')) {
        $res['the_point_records'] = ThePointRecord_R1::collection($this->the_point_records);
      }
    }
    return $res;
  }
}
