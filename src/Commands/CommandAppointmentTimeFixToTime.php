<?php

namespace Wasateam\Laravelapistone\Commands;

use Illuminate\Console\Command;
use Wasateam\Laravelapistone\Helpers\TimeHelper;

class CommandAppointmentTimeFixToTime extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'appointment:timestrtotime';

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
    $models = \Wasateam\Laravelapistone\Models\Appointment::all();
    foreach ($models as $model) {
      $model->start_time = TimeHelper::timeFixFromStrToTime($model->start_time);
      $model->end_time   = TimeHelper::timeFixFromStrToTime($model->end_time);
      $model->save();
    }
    // $users = User::all();
    // foreach ($users as $user) {
    //   $user = UserHelper::generateInviteNo($user, 'Wasateam\Laravelapistone\Models\User');
    // }
    // return 0;
  }
}
