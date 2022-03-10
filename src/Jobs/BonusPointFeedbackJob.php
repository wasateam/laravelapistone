<?php

namespace Wasateam\Laravelapistone\Jobs;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Wasateam\Laravelapistone\Helpers\ShopHelper;
use Wasateam\Laravelapistone\Models\ShopCampaign;
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
    //紅利點數回饋
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
        ShopHelper::createShopReturnRecord($shop_order, $today_bonus_campaign->id, $bonus_point_feedback, 'get');
      }
    }
  }
}
