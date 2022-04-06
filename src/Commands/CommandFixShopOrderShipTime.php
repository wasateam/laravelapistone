<?php

namespace Wasateam\Laravelapistone\Commands;

use Illuminate\Console\Command;

class CommandFixShopOrderShipTime extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'fixshopordershiptime';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Command description';

  /**
   * Create a new command instance.
   *
   * @return void
   */
  public function __construct()
  {
    parent::__construct();
  }

  /**
   * Execute the console command.
   *
   * @return int
   */
  public function handle()
  {
    $shop_orders = \Wasateam\Laravelapistone\Models\ShopOrder::all();
    foreach ($shop_orders as $shop_order) {
      if ($shop_order->shop_ship_time_setting) {
        $shop_order->ship_start_time = $shop_order->shop_ship_time_setting->start_time;
        $shop_order->ship_end_time   = $shop_order->shop_ship_time_setting->end_time;
        $shop_order->save();
      }
    }
    return 0;
  }
}
