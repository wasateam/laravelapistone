<?php

namespace Wasateam\Laravelapistone\Commands;

use Illuminate\Console\Command;
use Wasateam\Laravelapistone\Models\ShopOrder;

class CommandFillShopOrderInvoiceJob extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'fillshoporderinvoicejob';

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

    $shop_ordes = ShopOrder::doesntHave('invoice_jobs')->count();
    \Log::info($shop_ordes);
    return 0;
  }
}
