<?php

namespace Wasateam\Laravelapistone\Commands;

use Illuminate\Console\Command;

class CommandMakeUpBonusPointsFromShopOrder extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'mkbpfso';

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
    $shop_orders = \Wasateam\Laravelapistone\Models\ShopOrder::where('invoice_status', 'done')
      ->whereDoesntHave('bonus_point_record_new_show_order')->get();
    \Log::info(count($shop_orders));
    foreach ($shop_orders as $shop_order) {
      \Log::info($shop_order->id);
      \Wasateam\Laravelapistone\Helpers\ShopHelper::createBonusPointFromShopOrder($shop_order);
    }
    return 0;
  }
}
