<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class AuthSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $model           = config('stone.auth.model');
    $model_name      = config('stone.auth.model_name');
    $default_scopes  = config('stone.auth.default_scopes');
    $user            = new $model;
    $user->email     = "wasa@wasateam.com";
    $user->name      = 'wasa';
    $user->password  = '123123';
    $user->is_active = 1;
    $user->scopes    = $default_scopes;
    $user->save();
  }
}
