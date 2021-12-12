<?php

namespace Wasateam\Laravelapistone;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Wasateam\Laravelapistone\Commands\CommandStoneTest;

class StoneServiceProvider extends ServiceProvider
{
  public function boot()
  {
    # Publishes
    # CMS
    $this->publishes([
      __DIR__ . '/../config/stone-cms.php' => $this->app->configPath('stone.php'),
    ], 'stone-config-cms');

    # WEBAPI
    $this->publishes([
      __DIR__ . '/../config/stone-webapi.php' => $this->app->configPath('stone.php'),
    ], 'stone-config-webapi');

    if (config('stone.mode') == 'cms') {

      # Routes
      Route::middleware('api')->prefix('api')->group(function () {
        $this->loadRoutesFrom(__DIR__ . '/../routes/api-cms.php');
      });

      if (config('stone.migration')) {

        # GeneralContent
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations/general_content');

        # Admin
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations/admin');
        # AdminRole
        if (config('stone.admin_role')) {
          $this->loadMigrationsFrom(__DIR__ . '/../database/migrations/admin_role');
        }
        # AdminGroup
        if (config('stone.admin_group')) {
          $this->loadMigrationsFrom(__DIR__ . '/../database/migrations/admin_group');
        }
        # AdminSystemClass
        if (config('stone.admin_system_class')) {
          $this->loadMigrationsFrom(__DIR__ . '/../database/migrations/admin_system_class');
        }

        # CMSLog
        if (config('stone.log')) {
          $this->loadMigrationsFrom(__DIR__ . '/../database/migrations/cms_log');
        }

        # WebLog
        if (config('stone.web_log')) {
          $this->loadMigrationsFrom(__DIR__ . '/../database/migrations/web_log');
        }

        # Pocket
        if (config('stone.pocket')) {
          $this->loadMigrationsFrom(__DIR__ . '/../database/migrations/pocket_image');
          $this->loadMigrationsFrom(__DIR__ . '/../database/migrations/pocket_file');
        }

        # Tulpa
        if (config('stone.tulpa')) {
          $this->loadMigrationsFrom(__DIR__ . '/../database/migrations/tulpa');
        }

        # User
        if (config('stone.user')) {
          $this->loadMigrationsFrom(__DIR__ . '/../database/migrations/user');
        }

        # UserDeviceToken
        if (config('stone.user_device_token')) {
          $this->loadMigrationsFrom(__DIR__ . '/../database/migrations/user_device_token');
        }

        # Socialite
        if (config('stone.socialite')) {
          if (config('stone.socialite.facebook')) {
            $this->loadMigrationsFrom(__DIR__ . '/../database/migrations/socialite/facebook');
          }
          if (config('stone.socialite.google')) {
            $this->loadMigrationsFrom(__DIR__ . '/../database/migrations/socialite/google');
          }
          if (config('stone.socialite.line')) {
            $this->loadMigrationsFrom(__DIR__ . '/../database/migrations/socialite/line');
          }
        }

        # WsBlog
        if (config('stone.ws_blog')) {
          $this->loadMigrationsFrom(__DIR__ . '/../database/migrations/ws_blog');
        }

        # Tag
        if (config('stone.tag')) {
          $this->loadMigrationsFrom(__DIR__ . '/../database/migrations/tag');
        }

        # Area
        if (config('stone.area')) {
          $this->loadMigrationsFrom(__DIR__ . '/../database/migrations/area');
        }

        # SystemClass
        if (config('stone.system_class')) {
          $this->loadMigrationsFrom(__DIR__ . '/../database/migrations/system_class');
        }

        # ContactRequest
        if (config('stone.contact_request')) {
          $this->loadMigrationsFrom(__DIR__ . '/../database/migrations/contact_request');
        }

        # UserDeviceToken
        if (config('stone.user_device_token')) {
          $this->loadMigrationsFrom(__DIR__ . '/../database/migrations/user_device_token');
        }

        # ServiceStore
        if (config('stone.service_store')) {
          $this->loadMigrationsFrom(__DIR__ . '/../database/migrations/service_store');
        }

        # Appointment
        if (config('stone.appointment')) {
          $this->loadMigrationsFrom(__DIR__ . '/../database/migrations/appointment');
        }

        # User Address
        if (config('stone.user.address')) {
          $this->loadMigrationsFrom(__DIR__ . '/../database/migrations/user_address');
        }

        # ServicePlan
        if (config('stone.service_plan')) {
          $this->loadMigrationsFrom(__DIR__ . '/../database/migrations/service_plan');
        }

        # PinCard
        if (config('stone.pin_card')) {
          $this->loadMigrationsFrom(__DIR__ . '/../database/migrations/pin_card');
        }

        # Locale
        if (config('stone.locale')) {
          $this->loadMigrationsFrom(__DIR__ . '/../database/migrations/locale');
        }

        # Notification
        if (config('stone.notification')) {
          $this->loadMigrationsFrom(__DIR__ . '/../database/migrations/notification');
        }

        # Shop
        if (config('stone.shop')) {
          $this->loadMigrationsFrom(__DIR__ . '/../database/migrations/shop');
        }

        # FeaturedClass
        if (config('stone.featured_class')) {
          $this->loadMigrationsFrom(__DIR__ . '/../database/migrations/featured_class');
        }

        # Acumatica
        if (config('stone.acumatica')) {
          $this->loadMigrationsFrom(__DIR__ . '/../database/migrations/acumatica');
        }

        # News Banner
        if (config('stone.news_banner')) {
          $this->loadMigrationsFrom(__DIR__ . '/../database/migrations/news_banner');
        }
      }

      $this->publishes([
        __DIR__ . '/../resources/views/wasa/pdf' => resource_path('views/wasa/pdf'),
      ], 'views-shop-order-picking');

      # Publish
      $publishes                                         = [];
      $publishes[__DIR__ . '/../config/auth_admin.php']  = $this->app->configPath('auth.php');
      $publishes[__DIR__ . '/../database/seeders/admin'] = database_path('seeders');
      $publishes[__DIR__ . '/../resources/lang/']        = resource_path('lang/');
      $this->publishes($publishes, 'stone-setup-cms');
    }

    if (config('stone.mode') == 'webapi') {
      Route::middleware('api')->prefix('api')->group(function () {
        $this->loadRoutesFrom(__DIR__ . '/../routes/api-webapi.php');
      });

      # Publish
      $publishes                                       = [];
      $publishes[__DIR__ . '/../config/auth_user.php'] = $this->app->configPath('auth.php');
      $publishes[__DIR__ . '/../resources/lang/']      = resource_path('lang/');
      $this->publishes($publishes, 'stone-setup-webapi');
    }

    // OLD 2021-10
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
