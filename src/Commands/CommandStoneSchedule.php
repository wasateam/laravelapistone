<?php

namespace Wasateam\Laravelapistone\Commands;

use Illuminate\Console\Command;

class CommandStoneSchedule extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'stone:schedule';

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
    if (config('stone.schedule.log')) {
      \Log::info('Stone Schedule');
    }
    \Wasateam\Laravelapistone\Helpers\ScheduleHelper::stoneWork();
    return Command::SUCCESS;
  }
}
