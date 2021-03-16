<?php

namespace Wasateam\Laravelapistone;

use Illuminate\Support\ServiceProvider;

class StoneServiceProvider extends ServiceProvider
{
  public function boot()
  {
    $this->publishes([
      __DIR__ . '/../config/stone.php' => $this->app->configPath('stone.php'),
    ], 'stone-config');

    $this->publishes([
      __DIR__ . '/../config/auth_user.php' => $this->app->configPath('auth.php'),
    ], 'auth-user-config');

    $this->publishes([
      __DIR__ . '/../config/auth_admin.php' => $this->app->configPath('auth.php'),
    ], 'auth-admin-config');

    $this->publishes([
      __DIR__ . '/../database/migrations/admin' => database_path('migrations'),
    ], 'migrations-admin');

    $this->publishes([
      __DIR__ . '/../database/migrations/user' => database_path('migrations'),
    ], 'migrations-user');

    $this->publishes([
      __DIR__ . '/../database/migrations/image' => database_path('migrations'),
    ], 'migrations-image');

    $this->publishes([
      __DIR__ . '/../database/migrations/file' => database_path('migrations'),
    ], 'migrations-file');

    $this->publishes([
      __DIR__ . '/../database/seeders/auth' => database_path('seeders'),
    ], 'seeder-auth');

    $this->publishes([
      __DIR__ . '/../database/migrations/socialite' => database_path('migrations'),
    ], 'migrations-socialite');

    $this->publishes([
      __DIR__ . '/../database/migrations/tulpa' => database_path('migrations'),
    ], 'migrations-tulpa');

  }
}
