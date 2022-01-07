<?php

namespace Wasateam\Laravelapistone\Jobs;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Wasateam\Laravelapistone\Models\ShopCampaign;
use Wasateam\Laravelapistone\Models\ShopCampaignShopOrder;
use Wasateam\Laravelapistone\Models\ShopOrder;

class BonusPointFeedbackJob implements ShouldQueue
{
  use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

  public $tries = 10;
  public $shop_order_id;

  /**
   * Create a new job instance.
   *
   * @return void
   */
  public function __construct($shop_order_id)
  {
    $this->shop_order_id = $shop_order_id;
  }

  /**
   * Execute the job.
   *
   * @return void
   */
  public function handle()
  {
    $shop_order = ShopOrder::find($this->shop_order_id);
    // get today shop_campaign bonus-point-feedback
    $today                = Carbon::now()->format('Y-m-d');
    $today_bonus_campaign = ShopCampaign::whereDate('start_date', '<=', $today)->where('end_date', ">=", $today)->where('type', 'bonus-point-feedback')->first();
    if ($today_bonus_campaign) {
      if ($today_bonus_campaign->condition == 'rate') {
        //bonus_point_feedback
        $bonus_point_feedback = $shop_order->order_price * $today_bonus_campaign->feedback_rate;

        $user              = $shop_order->user;
        $user->bonus_point = $user->bonus_point + $bonus_point_feedback;
        $user->save();
        //create shop_campaign_shop_order
        $shop_campaign_shop_order                   = new ShopCampaignShopOrder;
        $shop_campaign_shop_order->type             = $today_bonus_campaign->type;
        $shop_campaign_shop_order->name             = $today_bonus_campaign->name;
        $shop_campaign_shop_order->full_amount      = $today_bonus_campaign->full_amount;
        $shop_campaign_shop_order->discount_percent = $today_bonus_campaign->discount_percent;
        $shop_campaign_shop_order->discount_amount  = $today_bonus_campaign->discount_amount;
        $shop_campaign_shop_order->condition        = $today_bonus_campaign->condition;
        $shop_campaign_shop_order->feedback_rate    = $today_bonus_campaign->feedback_rate;
        $shop_campaign_shop_order->shop_campaign_id = $today_bonus_campaign->id;
        $shop_campaign_shop_order->shop_campaign_id = $today_bonus_campaign->id;
        $shop_campaign_shop_order->shop_order_id    = $this->shop_order_id;
        $shop_campaign_shop_order->user_id          = $user->id;

      }
    }
  }
}
