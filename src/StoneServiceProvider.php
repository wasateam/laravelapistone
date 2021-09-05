<?php

namespace Wasateam\Laravelapistone;

use Illuminate\Support\ServiceProvider;
use Wasateam\Laravelapistone\Commands\CommandStoneTest;

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
      __DIR__ . '/../database/migrations/pocket_image' => database_path('migrations'),
    ], 'migrations-pocket-image');

    $this->publishes([
      __DIR__ . '/../database/migrations/pocket_file' => database_path('migrations'),
    ], 'migrations-pocket-file');

    $this->publishes([
      __DIR__ . '/../database/seeders/auth' => database_path('seeders'),
    ], 'seeder-auth');

    $this->publishes([
      __DIR__ . '/../database/seeders/admin' => database_path('seeders'),
    ], 'seeder-admin');

    $this->publishes([
      __DIR__ . '/../database/migrations/socialite' => database_path('migrations'),
    ], 'migrations-socialite');

    $this->publishes([
      __DIR__ . '/../database/migrations/tulpa' => database_path('migrations'),
    ], 'migrations-tulpa');

    $this->publishes([
      __DIR__ . '/../database/migrations/ws_blog' => database_path('migrations'),
    ], 'migrations-ws_blog');

    $this->publishes([
      __DIR__ . '/../database/migrations/tag' => database_path('migrations'),
    ], 'migrations-tag');

    $this->publishes([
      __DIR__ . '/../database/migrations/locale' => database_path('migrations'),
    ], 'migrations-locale');

    $this->publishes([
      __DIR__ . '/../database/migrations/web_log' => database_path('migrations'),
    ], 'migrations-web_log');

    $this->publishes([
      __DIR__ . '/../database/migrations/cms_log' => database_path('migrations'),
    ], 'migrations-cms_log');

    $this->publishes([
      __DIR__ . '/../database/migrations/area' => database_path('migrations'),
    ], 'migrations-area');

    $this->publishes([
      __DIR__ . '/../database/migrations/system_class' => database_path('migrations'),
    ], 'migrations-system_class');

    $this->publishes([
      __DIR__ . '/../database/migrations/contact_request' => database_path('migrations'),
    ], 'migrations-contact_request');

    $this->publishes([
      __DIR__ . '/../resources/views/wasa/mail' => resource_path('views/wasa/mail'),
    ], 'views-mail');

    $this->publishes([
      __DIR__ . '/../database/migrations/admin_role' => database_path('migrations'),
    ], 'migrations-admin-role');

    $this->publishes([
      __DIR__ . '/../database/migrations/admin_system_class' => database_path('migrations'),
    ], 'migrations-admin-system-class');

    $this->publishes([
      __DIR__ . '/../database/migrations/user_device_token' => database_path('migrations'),
    ], 'migrations-user_device_token');

    $this->publishes([
      __DIR__ . '/../database/migrations/app' => database_path('migrations'),
    ], 'migrations-app');

    $this->publishes([
      __DIR__ . '/../database/migrations/group' => database_path('migrations'),
    ], 'migrations-group');

    $this->publishes([
      __DIR__ . '/../database/migrations/admin_group' => database_path('migrations'),
    ], 'migrations-admin-group');

    $this->publishes([
      __DIR__ . '/../database/migrations/service_store' => database_path('migrations'),
    ], 'migrations-service-store');
    
    $this->publishes([
      __DIR__ . '/../database/migrations/pin_card' => database_path('migrations'),
    ], 'migrations-pin_card');
    
    $this->publishes([
      __DIR__ . '/../database/migrations/service_plan' => database_path('migrations'),
    ], 'migrations-service_plan');

    $this->publishes([
      __DIR__ . '/../database/migrations/appointment' => database_path('migrations'),
    ], 'migrations-appointment');

    if ($this->app->runningInConsole()) {
      $this->commands([
        CommandStoneTest::class,
      ]);
    }
  }
}
