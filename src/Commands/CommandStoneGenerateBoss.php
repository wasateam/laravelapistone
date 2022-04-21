<?php

namespace Wasateam\Laravelapistone\Commands;

use Illuminate\Console\Command;


/**
 * Generate Admin with boss scope
 *
 */
class CommandStoneGenerateBoss extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'stone:generateboss';

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
    $model           = config('stone.auth.model');
    $model_name      = config('stone.auth.model_name');
    $default_scopes  = config('stone.auth.default_scopes');
    $user            = new $model;
    $user->email     = "boss@wasateam.com";
    $user->name      = 'boss';
    $user->password  = '123123';
    $user->is_active = 1;
    $user->scopes    = ['boss', 'admin'];
    $user->save();
    return 0;
  }
}
