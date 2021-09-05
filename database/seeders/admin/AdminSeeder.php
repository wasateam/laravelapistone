<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
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
    $user->email     = "boss@wasateam.com";
    $user->name      = 'boss';
    $user->password  = '123123';
    $user->is_active = 1;
    $user->scopes    = ['boss', 'admin'];
    $user->save();
    $user            = new $model;
    $user->email     = "admin@wasateam.com";
    $user->name      = 'admin';
    $user->password  = '123123';
    $user->is_active = 1;
    $user->scopes    = ['admin'];
    $user->save();
  }
}
