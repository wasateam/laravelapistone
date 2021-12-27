<?php

namespace Wasateam\Laravelapistone\Commands;

use Illuminate\Console\Command;
use Wasateam\Laravelapistone\Helpers\AppointmentHelper;

class CommandStoneWork extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'stone:work';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Stone works';

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
    AppointmentHelper::appointmentNotify();
    return 0;
  }
}
