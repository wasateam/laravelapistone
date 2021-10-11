<?php

namespace Wasateam\Laravelapistone\Helpers;

use Illuminate\Support\Facades\Route;
use Wasateam\Laravelapistone\Controllers\AdminGroupController;
use Wasateam\Laravelapistone\Controllers\AdminRoleController;
use Wasateam\Laravelapistone\Controllers\AdminScopeController;
use Wasateam\Laravelapistone\Controllers\AppController;
use Wasateam\Laravelapistone\Controllers\AppointmentController;
use Wasateam\Laravelapistone\Controllers\AppRoleController;
use Wasateam\Laravelapistone\Controllers\AreaController;
use Wasateam\Laravelapistone\Controllers\AreaSectionController;
use Wasateam\Laravelapistone\Controllers\AuthController;
use Wasateam\Laravelapistone\Controllers\CalendarHighlightController;
use Wasateam\Laravelapistone\Controllers\CMSAdminController;
use Wasateam\Laravelapistone\Controllers\CmsLogController;
use Wasateam\Laravelapistone\Controllers\ContactRequestController;
use Wasateam\Laravelapistone\Controllers\LocaleController;
use Wasateam\Laravelapistone\Controllers\NotificationController;
use Wasateam\Laravelapistone\Controllers\PinCardController;
use Wasateam\Laravelapistone\Controllers\PocketFileController;
use Wasateam\Laravelapistone\Controllers\PocketImageController;
use Wasateam\Laravelapistone\Controllers\ServicePlanController;
use Wasateam\Laravelapistone\Controllers\ServicePlanItemController;
use Wasateam\Laravelapistone\Controllers\ServiceStoreCloseController;
use Wasateam\Laravelapistone\Controllers\ServiceStoreController;
use Wasateam\Laravelapistone\Controllers\ServiceStoreNotiController;
use Wasateam\Laravelapistone\Controllers\SnappyController;
use Wasateam\Laravelapistone\Controllers\SocialiteController;
use Wasateam\Laravelapistone\Controllers\SocialiteFacebookAccountController;
use Wasateam\Laravelapistone\Controllers\SocialiteGoogleAccountController;
use Wasateam\Laravelapistone\Controllers\SocialiteLineAccountController;
use Wasateam\Laravelapistone\Controllers\SystemClassController;
use Wasateam\Laravelapistone\Controllers\SystemSubclassController;
use Wasateam\Laravelapistone\Controllers\TagController;
use Wasateam\Laravelapistone\Controllers\TulpaCrossItemController;
use Wasateam\Laravelapistone\Controllers\TulpaPageController;
use Wasateam\Laravelapistone\Controllers\TulpaPageTemplateController;
use Wasateam\Laravelapistone\Controllers\TulpaSectionController;
use Wasateam\Laravelapistone\Controllers\UserAppInfoController;
use Wasateam\Laravelapistone\Controllers\UserController;
use Wasateam\Laravelapistone\Controllers\UserDeviceTokenController;
use Wasateam\Laravelapistone\Controllers\UserServicePlanController;
use Wasateam\Laravelapistone\Controllers\UserServicePlanItemController;
use Wasateam\Laravelapistone\Controllers\WebLogController;
use Wasateam\Laravelapistone\Controllers\WsBlogController;

class RoutesHelper
{
  public static function get_all_routes_name()
  {
    $routeCollection = Route::getRoutes();
    $routes          = [];
    foreach ($routeCollection as $key => $route) {
      $routes[] = $route->getAction();
    }
    return $routes;
  }

  public static function auth_routes($routes = [
    "signin",
    "signup",
    "signout",
    "userget",
    "userpatch",
    "passwordpatch",
    "avatarpatch",
    "forgetpassword",
  ]) {
    $model_name = config('stone.auth.model_name');
    $auth_scope = config('stone.auth.auth_scope');
    Route::group([
      'prefix' => 'auth',
    ], function () use ($routes, $model_name, $auth_scope) {
      if (in_array('signin', $routes)) {
        Route::post('/signin', [AuthController::class, 'signin']);
      }
      if (in_array('signup', $routes)) {
        Route::post('/signup', [AuthController::class, 'signup']);
      }
      Route::group([
        "middleware" => ["auth:{$model_name}", "scopes:{$auth_scope}"],
      ], function () use ($routes) {
        if (in_array('signout', $routes)) {
          Route::post('/signout', [AuthController::class, 'signout']);
        }
        if (in_array('userget', $routes)) {
          Route::get('/user', [AuthController::class, 'user']);
        }
        if (in_array('userpatch', $routes)) {
          Route::patch('/user', [AuthController::class, 'update']);
        }
        if (in_array('passwordpatch', $routes)) {
          Route::patch('/user/password', [AuthController::class, 'password_update']);
        }
        if (in_array('avatarpatch', $routes)) {
          if (env('SIGNED_URL_MODE') == 'gcs') {
            Route::get("/avatar/upload_url/{filename}", [AuthController::class, 'get_avatar_upload_url']);
          } else {
            Route::put("/avatar/{filename}", [AuthController::class, 'avatar_upload']);
          }
        }
      });

      if (in_array('forgetpassword', $routes)) {
        Route::post("/forgetpassword/request", [AuthController::class, 'forget_password_request']);
        Route::group([
          "middleware" => ["signed"],
        ], function () {
          Route::post('/forgetpassword/patch/{user_id}', [AuthController::class, 'forget_password_patch'])->name('forget_password_patch');
        });
      }
    });
  }

  public static function cms_boss_routes()
  {

    # Admin
    if (config('stone.admin_blur')) {
      Route::resource('cmser', CMSAdminController::class)->only([
        'index', 'show', 'store', 'update', 'destroy',
      ])->shallow();
    } else {
      Route::resource('admin', CMSAdminController::class)->only([
        'index', 'show', 'store', 'update', 'destroy',
      ])->shallow();
    }

    # AdminRole
    if (config('stone.admin_role')) {
      if (config('stone.admin_blur')) {
        Route::resource('cmser_role', AdminRoleController::class)->only([
          'index', 'show', 'store', 'update', 'destroy',
        ])->shallow();
      } else {
        Route::resource('admin_role', AdminRoleController::class)->only([
          'index', 'show', 'store', 'update', 'destroy',
        ])->shallow();
      }
    }

    # AdminGroup
    if (config('stone.admin_group')) {
      if (config('stone.admin_blur')) {
        Route::resource('cmser_group', AdminGroupController::class)->only([
          'index', 'show', 'store', 'update', 'destroy',
        ])->shallow();
      } else {
        Route::resource('admin_group', AdminGroupController::class)->only([
          'index', 'show', 'store', 'update', 'destroy',
        ])->shallow();
      }
    }

    # CMS Log
    Route::resource('cms_log', CmsLogController::class)->only([
      'index',
      'show',
    ])->shallow();

    # Locale
    Route::resource('locale', LocaleController::class)->only([
      'index', 'show', 'store', 'update', 'destroy',
    ])->shallow();
  }

  public static function cms_admin_routes()
  {
    # Pocket
    if (config('stone.pocket')) {

      Route::get('pocket_image/upload_url', [PocketImageController::class, 'get_upload_url']);
      Route::resource('pocket_image', PocketImageController::class)->only([
        'index', 'store', 'destroy',
      ])->shallow();
      Route::patch('pocket_image/public_url/{id}', [PocketImageController::class, 'public_url']);
      Route::get('pocket_file/upload_url', [PocketFileController::class, 'get_upload_url']);
      Route::resource('pocket_file', PocketFileController::class)->only([
        'index', 'store', 'destroy',
      ])->shallow();
      Route::patch('pocket_file/public_url/{id}', [PocketFileController::class, 'public_url']);
    }

    # User
    if (config('stone.user')) {
      Route::resource('user', UserController::class)->only([
        'index', 'show', 'store', 'update', 'destroy',
      ])->shallow();
    }

    # Socialite
    if (config('stone.socialite')) {
      if (config('stone.socialite.google')) {
        Route::resource('user', SocialiteGoogleAccountController::class)->only([
          'index', 'show', 'store', 'update', 'destroy',
        ])->shallow();
      }
      if (config('stone.socialite.facebook')) {
        Route::resource('user', SocialiteFacebookAccountController::class)->only([
          'index', 'show', 'store', 'update', 'destroy',
        ])->shallow();
      }
      if (config('stone.socialite.line')) {
        Route::resource('user', SocialiteLineAccountController::class)->only([
          'index', 'show', 'store', 'update', 'destroy',
        ])->shallow();
      }
    }

    # Blog
    if (config('stone.ws_blog')) {
      Route::resource('ws_blog', WsBlogController::class)->only([
        'index', 'show', 'store', 'update', 'destroy',
      ])->shallow();
      Route::get('/ws_blog/image/upload_url', [WsBlogController::class, 'image_get_upload_url']);
    }

    # Tag
    if (config('stone.tag')) {
      Route::resource('tag', TagController::class)->only([
        'index', 'show', 'store', 'update', 'destroy',
      ])->shallow();
    }

    # Area
    if (config('stone.area')) {
      Route::resource('area', AreaController::class)->only([
        'index', 'show', 'store', 'update', 'destroy',
      ])->shallow();
      Route::resource('area_section', AreaSectionController::class)->only([
        'index', 'show', 'store', 'update', 'destroy',
      ])->shallow();
    }

    # SystemClass
    if (config('stone.system_class')) {
      Route::resource('system_class', SystemClassController::class)->only([
        'index', 'show', 'store', 'update', 'destroy',
      ])->shallow();
      Route::resource('system_subclass', SystemSubclassController::class)->only([
        'index', 'show', 'store', 'update', 'destroy',
      ])->shallow();
    }

    # ContactRequest
    if (config('stone.contact_request')) {
      Route::resource('contact_request', ContactRequestController::class)->only([
        'index', 'show', 'store', 'update', 'destroy',
      ])->shallow();
    }

    # UserDeviceToken
    if (config('stone.user_device_token')) {
      Route::resource('user_device_token', UserDeviceTokenController::class)->only([
        'index', 'show', 'store', 'update', 'destroy',
      ])->shallow();
    }

    # ServiceStore
    if (config('stone.service_store')) {
      Route::resource('service_store', ServiceStoreController::class)->only([
        'index', 'show', 'store', 'update', 'destroy',
      ])->shallow();
      Route::resource('service_store_noti', ServiceStoreNotiController::class)->only([
        'index', 'show', 'store', 'update', 'destroy',
      ])->shallow();
      Route::resource('service_store_close', ServiceStoreCloseController::class)->only([
        'index', 'show', 'store', 'update', 'destroy',
      ])->shallow();
    }

    # Appointment
    if (config('stone.appointment')) {
      Route::resource('appointment', AppointmentController::class)->only([
        'index', 'show', 'store', 'update', 'destroy',
      ])->shallow();
    }

    # ServicePlan
    if (config('stone.service_plan')) {
      Route::resource('service_plan', ServicePlanController::class)->only([
        'index', 'show', 'store', 'update', 'destroy',
      ])->shallow();
      Route::resource('user_service_plan', UserServicePlanController::class)->only([
        'index', 'show', 'store', 'update', 'destroy',
      ])->shallow();
      Route::resource('service_plan_item', ServicePlanItemController::class)->only([
        'index', 'show', 'store', 'update', 'destroy',
      ])->shallow();
      Route::resource('user_service_plan_item', UserServicePlanItemController::class)->only([
        'index', 'show', 'store', 'update', 'destroy',
      ])->shallow();
    }

    # Pin Card
    if (config('stone.pin_card')) {
      Route::resource('pin_card', PinCardController::class)->only([
        'index', 'show', 'store', 'update', 'destroy',
      ])->shallow();
      Route::post('pin_card/generate', [PinCardController::class, 'generate']);
      Route::get('pin_card/export/excel/signedurl', [PinCardController::class, 'export_excel_signedurl']);
    }

    # Tulpa
    if (config('stone.tulpa')) {
      Route::get('/tulpa_page/image/upload_url', [TulpaPageController::class, 'image_get_upload_url']);
      Route::resource('tulpa_page', TulpaPageController::class)->only([
        'index', 'show', 'store', 'update', 'destroy',
      ])->shallow();
      Route::resource('tulpa_cross_item', TulpaCrossItemController::class)->only([
        'index', 'show', 'store', 'update', 'destroy',
      ])->shallow();
      Route::resource('tulpa_page_template', TulpaPageTemplateController::class)->only([
        'index', 'show', 'store', 'update', 'destroy',
      ])->shallow();
      Route::resource('tulpa_section', TulpaSectionController::class)->only([
        'index', 'show', 'store', 'update', 'destroy',
      ])->shallow();
    }

    # Notification
    Route::resource('notification', NotificationController::class)->only([
      'index',
      'show',
      'destroy',
    ])->shallow();
  }

  public static function cms_public_routes()
  {
    if (config('stone.pin_card')) {
      Route::group([
        "middleware" => ["signed"],
      ], function () {
        Route::get('pin_card/export/excel', [PinCardController::class, 'export_excel'])->name('pin_card_export_excel');
      });
    }
  }

  public static function webapi_auth_routes()
  {

  }

  public static function webapi_public_routes()
  {

    # Tulpa
    if (config('stone.tulpa')) {
      Route::get('tulpa_page', [TulpaPageController::class, 'index']);
      Route::get('tulpa_page/page', [TulpaPageController::class, 'show']);
      Route::resource('tulpa_page_template', TulpaPageTemplateController::class)->only([
        'index',
        'show',
      ])->shallow();
      Route::resource('tulpa_section', TulpaSectionController::class)->only([
        'index',
        'show',
      ])->shallow();
    }

    # Blog
    if (config('stone.ws_blog')) {
      Route::resource('ws_blog', WsBlogController::class)->only([
        'index',
        'show',
      ])->shallow();
      Route::get('ws_blog/{id}/read', [WsBlogController::class, 'read']);
    }

    # ServiceStore
    if (config('stone.service_store')) {
      Route::resource('service_store', ServiceStoreController::class)->only([
        'index',
        'show',
      ])->shallow();
      Route::resource('service_store_noti', ServiceStoreNotiController::class)->only([
        'index',
        'show',
      ])->shallow();
      Route::resource('service_store_close', ServiceStoreCloseController::class)->only([
        'index',
        'show',
      ])->shallow();
    }

    # Socialite
    if (config('stone.socialite')) {
      if (config('stone.socialite.google')) {
        Route::get('signin/google', [SocialiteController::class, 'googleCallback']);
      }
      if (config('stone.socialite.facebook')) {
        Route::get('signin/facebook', [SocialiteController::class, 'facebookCallback']);
      }
      if (config('stone.socialite.line')) {
        Route::get('signin/line', [SocialiteController::class, 'lineCallback']);
      }
    }
  }

  public static function admin_routes($routes = [
    'index',
    'show',
    'store',
    'update',
    'destroy',
  ]) {
    // Admin
    if (config('stone.admin_blur')) {
      Route::resource('cmser', CMSAdminController::class)->only($routes)->shallow();
    } else {
      Route::resource('admin', CMSAdminController::class)->only($routes)->shallow();
    }
  }

  public static function admin_groups($routes = [
    'index',
    'show',
    'store',
    'update',
    'destroy',
  ]) {
    if (config('stone.admin_blur')) {
      Route::resource('cmser_group', AdminGroupController::class)->only($routes)->shallow();
    } else {
      Route::resource('admin_group', AdminGroupController::class)->only($routes)->shallow();
    }
  }

  public static function admin_scope_routes($routes = [
    'index',
    'show',
    'store',
    'update',
    'destroy',
  ]) {
    if (config('stone.admin_blur')) {
      Route::resource('cmser_scope', AdminScopeController::class)->only($routes)->shallow();
    } else {
      Route::resource('admin_scope', AdminScopeController::class)->only($routes)->shallow();
    }
  }

  public static function admin_role_routes($routes = [
    'index',
    'show',
    'store',
    'update',
    'destroy',
  ]) {
    if (config('stone.admin_blur')) {
      Route::resource('cmser_role', AdminRoleController::class)->only($routes)->shallow();
    } else {
      Route::resource('admin_role', AdminRoleController::class)->only($routes)->shallow();
    }
  }

  public static function role_routes($routes = [
    'index',
    'show',
    'store',
    'update',
    'destroy',
  ]) {
    // Route::resource('admin_scope', TulpaPageController::class)->only($routes)->shallow();
  }

  public static function socialite_routes($routes = [
    "google",
    "facebook",
    "line",
  ]) {

    Route::group([
      'prefix' => 'auth',
    ], function () use ($routes) {

      if (in_array('google', $routes)) {
        Route::get('signin/google', [SocialiteController::class, 'googleCallback']);
      }
      if (in_array('facebook', $routes)) {
        Route::get('signin/facebook', [SocialiteController::class, 'facebookCallback']);
      }
      if (in_array('line', $routes)) {
        Route::get('signin/line', [SocialiteController::class, 'lineCallback']);
      }
    });
  }

  public static function tulpa_routes()
  {
    $mode = config('stone.mode');
    if ($mode == 'cms') {
      $routes = [
        'index',
        'show',
        'store',
        'update',
        'destroy',
      ];
      Route::get('/tulpa_page/image/upload_url', [TulpaPageController::class, 'image_get_upload_url']);
      Route::resource('tulpa_page', TulpaPageController::class)->only($routes)->shallow();
      Route::resource('tulpa_cross_item', TulpaCrossItemController::class)->only($routes)->shallow();
    } else {
      $routes = [
        'index',
        'show',
      ];
      Route::get('tulpa_page', [TulpaPageController::class, 'index']);
      Route::get('tulpa_page/page', [TulpaPageController::class, 'show']);
    }
    Route::resource('tulpa_page_template', TulpaPageTemplateController::class)->only($routes)->shallow();
    Route::resource('tulpa_section', TulpaSectionController::class)->only($routes)->shallow();
  }

  public static function ws_blog_routes($routes = [
    'index',
    'show',
    'store',
    'update',
    'destroy',
    'image_get_upload_url',
  ]) {
    $mode = config('stone.mode');
    if ($mode == 'cms') {
      Route::resource('ws_blog', WsBlogController::class)->only($routes)->shallow();
      if (in_array('image_get_upload_url', $routes)) {
        Route::get('/ws_blog/image/upload_url', [WsBlogController::class, 'image_get_upload_url']);
      }
    } else {
      Route::resource('ws_blog', WsBlogController::class)->only([
        'index',
        'show',
      ])->shallow();
      Route::get('ws_blog/{id}/read', [WsBlogController::class, 'read']);
    }
  }

  public static function tag_routes($routes = [
    'index',
    'show',
    'store',
    'update',
    'destroy',
  ]) {
    Route::resource('tag', TagController::class)->only($routes)->shallow();
  }

  public static function pocket_image_routes()
  {
    $storage_service = config('stone.storage.service');
    Route::get('pocket_image/upload_url', [PocketImageController::class, 'get_upload_url']);
    Route::resource('pocket_image', PocketImageController::class)->only([
      'index', 'store', 'destroy',
    ])->shallow();
    Route::patch('pocket_image/public_url/{id}', [PocketImageController::class, 'public_url']);
  }

  public static function pocket_file_routes()
  {
    $storage_service = config('stone.storage.service');
    if ($storage_service == 'gcs') {
      Route::get('pocket_file/upload_url', [PocketFileController::class, 'get_upload_url']);
    }
    Route::resource('pocket_file', PocketFileController::class)->only([
      'index', 'store', 'destroy',
    ])->shallow();
    Route::patch('pocket_file/public_url/{id}', [PocketFileController::class, 'public_url']);
  }

  public static function user_crud_routes($routes = [
    'index',
    'show',
    'store',
    'update',
    'destroy',
    // 'image_get_upload_url',
    // 'image_upload_complete',
  ]) {
    if (config('stone.mode') == 'cms') {
      Route::resource('user', UserController::class)->only($routes)->shallow();
    }
  }

  public static function locale_routes($routes = [
    'index',
    'show',
    'store',
    'update',
    'destroy',
  ]) {
    Route::resource('locale', LocaleController::class)->only($routes)->shallow();
  }

  public static function cms_log_routes($routes = [
    'index',
    'show',
  ]) {
    Route::resource('cms_log', CmsLogController::class)->only($routes)->shallow();
  }

  public static function web_log_routes($routes = [
    'index',
    'show',
  ]) {
    Route::resource('web_log', WebLogController::class)->only($routes)->shallow();
  }

  public static function area_routes($routes = [
    'index',
    'show',
    'store',
    'update',
    'destroy',
  ]) {
    Route::resource('area', AreaController::class)->only($routes)->shallow();
    Route::resource('area.area_section', AreaSectionController::class)->only($routes)->shallow();
  }

  public static function system_class_routes($routes = [
    'index',
    'show',
    'store',
    'update',
    'destroy',
  ]) {
    $mode = config('stone.mode');
    if ($mode == 'cms') {
      Route::resource('system_class', SystemClassController::class)->only($routes)->shallow();
    } else {
      Route::resource('area.system_class', SystemClassController::class)->only([
        'show',
      ])->shallow();
      Route::get('area/{area}/system_class', [SystemClassController::class, 'index_with_area']);
    }
    Route::resource('system_class.system_subclass', SystemSubclassController::class)->only($routes)->shallow([
      'index',
      'show',
    ]);
  }

  public static function contact_request_routes($routes = [
    'index',
    'show',
    'store',
    'update',
    'destroy',
  ]) {
    Route::resource('contact_request', ContactRequestController::class)->only($routes)->shallow();
  }

  public static function snappy_routes()
  {
    Route::get('snappy/test', [SnappyController::class, 'test']);

  }

  public static function user_device_token_routes()
  {

    $mode = config('stone.mode');
    if ($mode == 'cms') {
      Route::resource('user_device_token', UserDeviceTokenController::class)->only([
        'index',
        'show',
        'store',
        'update',
        'destroy',
      ])->shallow();
    } else {
      Route::post('device_token', [UserDeviceTokenController::class, 'active']);
      Route::delete('device_token', [UserDeviceTokenController::class, 'deactive']);
    }
  }

  public static function notification_routes()
  {
    $mode = config('stone.mode');
    if ($mode == 'cms') {
      Route::resource('notification', NotificationController::class)->only([
        'index',
        'show',
        'destroy',
      ])->shallow();
    } else {
      Route::get('notification', [NotificationController::class, 'user_index']);
      Route::get('notification/unread', [NotificationController::class, 'user_index_unread']);
      Route::post('notification/{id}/read', [NotificationController::class, 'user_read']);
      Route::post('notification/readall', [NotificationController::class, 'user_readall']);
    }
  }

  public static function app_routes($routes = [
    'index',
    'show',
    'store',
    'update',
    'destroy',
  ]) {
    if (config('stone.mode') == 'cms') {
      Route::resource('app', AppController::class)->only($routes)->shallow();
      Route::resource('user_app_info', UserAppInfoController::class)->only($routes)->shallow();
      Route::resource('app_role', AppRoleController::class)->only($routes)->shallow();
    }
  }

  public static function service_store_routes()
  {
    $mode = config('stone.mode');
    if ($mode == 'cms') {
      $routes = [
        'index',
        'show',
        'store',
        'update',
        'destroy',
      ];
      Route::resource('service_store', ServiceStoreController::class)->only($routes)->shallow();
      Route::resource('service_store_noti', ServiceStoreNotiController::class)->only($routes)->shallow();
      Route::resource('service_store_close', ServiceStoreCloseController::class)->only($routes)->shallow();
    } else {
      $routes = [
        'index',
        'show',
      ];
      Route::resource('service_store', ServiceStoreController::class)->only($routes)->shallow();
      Route::resource('service_store_noti', ServiceStoreNotiController::class)->only($routes)->shallow();
      Route::resource('service_store_close', ServiceStoreCloseController::class)->only($routes)->shallow();
    }
  }

  public static function service_plan_routes()
  {
    $mode = config('stone.mode');
    if ($mode == 'cms') {
      $routes = [
        'index',
        'show',
        'store',
        'update',
        'destroy',
      ];
      Route::resource('service_plan', ServicePlanController::class)->only($routes)->shallow();
      Route::resource('user_service_plan', UserServicePlanController::class)->only($routes)->shallow();
      Route::resource('service_plan_item', ServicePlanItemController::class)->only($routes)->shallow();
      Route::resource('user_service_plan_item', UserServicePlanItemController::class)->only($routes)->shallow();
    } else {
      // $routes = [
      //   'index',
      //   'show',
      // ];
      // Route::resource('service_plan', ServicePlanController::class)->only($routes)->shallow();
      // Route::resource('user_service_plan', UserServicePlanController::class)->only($routes)->shallow();
      // Route::resource('service_plan_item', ServicePlanItemController::class)->only($routes)->shallow();
      // Route::resource('user_service_plan_item', UserServicePlanItemController::class)->only($routes)->shallow();
    }
  }

  public static function pin_card_routes()
  {
    $mode = config('stone.mode');
    if ($mode == 'cms') {
      $routes = [
        'index',
        'show',
        'store',
        'update',
        'destroy',
      ];
      Route::resource('pin_card', PinCardController::class)->only($routes)->shallow();
      Route::post('pin_card/generate', [PinCardController::class, 'generate']);
      Route::get('pin_card/export/excel/signedurl', [PinCardController::class, 'export_excel_signedurl']);
    } else {
      Route::post('pin_card/register', [PinCardController::class, 'register']);
    }
  }

  public static function calendar_highlight_routes()
  {
    $mode = config('stone.mode');
    if ($mode == 'cms') {
      $routes = [
        'index',
        'show',
        'store',
        'update',
        'destroy',
      ];
      Route::resource('calendar_highlight', CalendarHighlightController::class)->only($routes)->shallow();
    } else {
      $routes = [
        'index',
        'show',
        'store',
        'update',
        'destroy',
      ];
      Route::resource('calendar_highlight', CalendarHighlightController::class)->only($routes)->shallow();
    }
  }

  public static function pin_card_excel_routes()
  {
    Route::group([
      "middleware" => ["signed"],
    ], function () {
      Route::get('pin_card/export/excel', [PinCardController::class, 'export_excel'])->name('pin_card_export_excel');
    });
  }

  public static function appointment_routes()
  {
    $mode = config('stone.mode');
    if ($mode == 'cms') {
      $routes = [
        'index',
        'show',
        'store',
        'update',
        'destroy',
      ];
      Route::resource('appointment', AppointmentController::class)->only($routes)->shallow();
      // Route::resource('appointment_available', AppointmentAvailableController::class)->only($routes)->shallow();
    } else {
      Route::resource('appointment', AppointmentController::class)->only([
        'index',
        'show',
        'store',
        'update',
        'destroy',
      ])->shallow();
      // Route::resource('appointment_available', AppointmentAvailableController::class)->only([
      //   'index',
      //   'show',
      // ])->shallow();
    }
  }
}
