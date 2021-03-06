<?php

namespace Wasateam\Laravelapistone;

use Illuminate\Support\ServiceProvider;

class StoneServiceProvider extends ServiceProvider
{
  public function boot()
  {
    $this->publishes([
      __DIR__ . '/../config/apistone.php' => $this->app->configPath('apistone.php'),
    ], 'apistone-config');

    $this->publishes([
      __DIR__ . '/../database/migrations/admin' => database_path('migrations'),
    ], 'apistone-migrations-admin');

    $this->publishes([
      __DIR__ . '/../database/migrations/user' => database_path('migrations'),
    ], 'apistone-migrations-user');

    $this->publishes([
      __DIR__ . '/../database/migrations/socialite' => database_path('migrations'),
    ], 'apistone-migrations-socialite');

    $this->publishes([
      __DIR__ . '/../database/migrations/tulpa' => database_path('migrations'),
    ], 'apistone-migrations-tulpa');
  }
}
