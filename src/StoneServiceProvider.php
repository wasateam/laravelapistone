<?php

namespace Wasateam\Laravelapistone;

use Illuminate\Support\ServiceProvider;

class StoneServiceProvider extends ServiceProvider
{
  /**
   * Bootstrap the application events.
   *
   * @return void
   */
  public function boot()
  {
    $this->publishes([
      __DIR__ . '/../config/apistone.php' => $this->app->configPath('apistone.php'),
    ]);
  }
}
