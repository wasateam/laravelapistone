<?php

namespace Wasateam\Laravelapistone\Commands;

use Illuminate\Console\Command;
use Wasateam\Laravelapistone\Helpers\EmailHelper;
use Wasateam\Laravelapistone\Helpers\FcmHelper;
use Wasateam\Laravelapistone\Tests\Feature\TestContactRequest;

class CommandStoneTest extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'stone:test {target}';

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
    $target = $this->argument('target');
    if ($target == 'contact_request') {
      $tester = new TestContactRequest();
      $tester->test();
    }
    if ($target == 'mail') {
      EmailHelper::sending_test(config('stone.contact_request.notify_mail'));
    }
    if ($target == 'fcm') {
      FcmHelper::sendMesssage('Test', '我是測試，安安', [
        "type"    => "FcmTset",
        "message" => "A data message",
      ]);
    }
    return 0;
  }
}
