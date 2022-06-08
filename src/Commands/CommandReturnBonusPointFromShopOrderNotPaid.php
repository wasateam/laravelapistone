<?php

namespace Wasateam\Laravelapistone\Commands;

use Illuminate\Console\Command;
use Wasateam\Laravelapistone\Helpers\ShopHelper;
use Wasateam\Laravelapistone\Models\BonusPointRecord;
use Wasateam\Laravelapistone\Models\ShopOrder;

/**
 *
 * Before using this command
 *
 *
 */
class CommandReturnBonusPointFromShopOrderNotPaid extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'rtbpfsonp';

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
    error_log('ReturnBonusPointFromShopOrderNotPaid');
    $shop_orders = ShopOrder::where('pay_status', 'not-paid')
      ->where('bonus_points_deduct', '>', 0)
      ->get();

    foreach ($shop_orders as $shop_order) {
      $bonus_point_record = BonusPointRecord::where('type', 'get')
        ->where('source', 'not_paid_shop_order')
        ->where('shop_order_id', $shop_order->id)
        ->first();
      if ($bonus_point_record) {
        continue;
      }
      ShopHelper::returnBonusPointsFromShopOrder($shop_order);
    }
    return Command::SUCCESS;
  }
}
