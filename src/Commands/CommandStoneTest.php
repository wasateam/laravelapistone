<?php

namespace Wasateam\Laravelapistone\Commands;

use Illuminate\Console\Command;
use Wasateam\Laravelapistone\Helpers\EcpayHelper;
use Wasateam\Laravelapistone\Helpers\EmailHelper;
use Wasateam\Laravelapistone\Helpers\FcmHelper;
use Wasateam\Laravelapistone\Tests\Feature\TestContactRequest;
use Wasateam\Laravelapistone\Tests\Feature\TestAllStone;

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
    if ($target == 'all') {
      $tester = new TestAllStone();
      $tester->test();
    }
    if ($target == 'contact_request') {
      $tester = new TestContactRequest();
      $tester->test();
    }
    if ($target == 'mail') {
      error_log('sending_test_mail');
      EmailHelper::sending_test(config('stone.mail.test_mail'));
    }
    if ($target == 'fcm') {
      FcmHelper::sendMesssage('Test', '我是測試，安安', [
        "type"    => "FcmTset",
        "message" => "A data message",
      ], ["cH8wqgZbnEvpn_pOrCD5rU:APA91bHeCVqRnJTMXscqvDZL5YNs3XNzzb7mtaVifqpuJeBHSm1uIcDXqcYrIMxagEyGQGh9Q6lsKi_rA-fvaAEbHcSL1Xuj__VqLhTN-MnXmvpGFZvttuWrKIpf12OQ6wLhs_MnJK0R"]);
    }
    if ($target == 'ecpay-inpay-token') {
      $pay_data = EcpayHelper::getInpayInitData();
      $token    = EcpayHelper::getMerchantToken($pay_data);
      error_log('token');
      error_log($token);
    }
    return 0;
  }
}
