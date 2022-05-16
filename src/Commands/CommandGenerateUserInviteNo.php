<?php

namespace Wasateam\Laravelapistone\Commands;

use Illuminate\Console\Command;
use Wasateam\Laravelapistone\Models\User;
use Wasateam\Laravelapistone\Helpers\UserHelper;

class CommandGenerateUserInviteNo extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'generate:userinviteno';

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
    $users = User::whereNull('invite_no')->get();
    foreach ($users as $user) {
      $user = UserHelper::generateInviteNo($user, 'Wasateam\Laravelapistone\Models\User');
    }
    return 0;
  }
}
