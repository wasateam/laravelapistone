<?php

namespace Wasateam\Laravelapistone\Commands;

use Illuminate\Console\Command;
use Wasateam\Laravelapistone\Models\ShopOrder;

class CommandGenerateFakeShopOrder extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'generate:fakeshoporder';

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
    ShopOrder::factory()
      ->paid()
      ->create();
    return 0;
  }
}
