<?php

namespace Wasateam\Laravelapistone\Helpers;

use Illuminate\Support\Facades\Route;
use Wasateam\Laravelapistone\Controllers\AcumaticaAppController;
use Wasateam\Laravelapistone\Controllers\AdminFinancePaymentRequestController;
use Wasateam\Laravelapistone\Controllers\AdminGroupController;
use Wasateam\Laravelapistone\Controllers\AdminRoleController;
use Wasateam\Laravelapistone\Controllers\AdminScopeController;
use Wasateam\Laravelapistone\Controllers\AppController;
use Wasateam\Laravelapistone\Controllers\AppointmentController;
use Wasateam\Laravelapistone\Controllers\AppRoleController;
use Wasateam\Laravelapistone\Controllers\AreaController;
use Wasateam\Laravelapistone\Controllers\AreaSectionController;
use Wasateam\Laravelapistone\Controllers\AuthController;
use Wasateam\Laravelapistone\Controllers\BonusPointRecordController;
use Wasateam\Laravelapistone\Controllers\CalendarHighlightController;
use Wasateam\Laravelapistone\Controllers\CMSAdminController;
use Wasateam\Laravelapistone\Controllers\CmsLogController;
use Wasateam\Laravelapistone\Controllers\ContactRequestAutoReplyController;
use Wasateam\Laravelapistone\Controllers\ContactRequestController;
use Wasateam\Laravelapistone\Controllers\ContactRequestNotifyMailController;
use Wasateam\Laravelapistone\Controllers\EcpayController;
use Wasateam\Laravelapistone\Controllers\FeaturedClassController;
use Wasateam\Laravelapistone\Controllers\GeneralUserInviteController;
use Wasateam\Laravelapistone\Controllers\LinePayController;
use Wasateam\Laravelapistone\Controllers\LocaleController;
use Wasateam\Laravelapistone\Controllers\NewsBannerController;
use Wasateam\Laravelapistone\Controllers\NewsBannerGroupController;
use Wasateam\Laravelapistone\Controllers\NewsController;
use Wasateam\Laravelapistone\Controllers\NotificationController;
use Wasateam\Laravelapistone\Controllers\PageCoverController;
use Wasateam\Laravelapistone\Controllers\PageSettingController;
use Wasateam\Laravelapistone\Controllers\PinCardController;
use Wasateam\Laravelapistone\Controllers\PocketFileController;
use Wasateam\Laravelapistone\Controllers\PocketImageController;
use Wasateam\Laravelapistone\Controllers\PrivacyController;
use Wasateam\Laravelapistone\Controllers\ServicePlanController;
use Wasateam\Laravelapistone\Controllers\ServicePlanItemController;
use Wasateam\Laravelapistone\Controllers\ServicePlanUsingRecordController;
use Wasateam\Laravelapistone\Controllers\ServiceStoreCloseController;
use Wasateam\Laravelapistone\Controllers\ServiceStoreController;
use Wasateam\Laravelapistone\Controllers\ServiceStoreNotiController;
use Wasateam\Laravelapistone\Controllers\ShopCampaignController;
use Wasateam\Laravelapistone\Controllers\ShopCampaignShopOrderController;
use Wasateam\Laravelapistone\Controllers\ShopCartController;
use Wasateam\Laravelapistone\Controllers\ShopCartProductController;
use Wasateam\Laravelapistone\Controllers\ShopClassController;
use Wasateam\Laravelapistone\Controllers\ShopFreeShippingController;
use Wasateam\Laravelapistone\Controllers\ShopNoticeClassController;
use Wasateam\Laravelapistone\Controllers\ShopNoticeController;
use Wasateam\Laravelapistone\Controllers\ShopOrderController;
use Wasateam\Laravelapistone\Controllers\ShopOrderShopProductController;
use Wasateam\Laravelapistone\Controllers\ShopOrderShopProductSpecController;
use Wasateam\Laravelapistone\Controllers\ShopOrderShopProductSpecSettingController;
use Wasateam\Laravelapistone\Controllers\ShopOrderShopProductSpecSettingItemController;
use Wasateam\Laravelapistone\Controllers\ShopProductController;
use Wasateam\Laravelapistone\Controllers\ShopProductCoverFrameController;
use Wasateam\Laravelapistone\Controllers\ShopProductExpectShipController;
use Wasateam\Laravelapistone\Controllers\ShopProductImportRecordController;
use Wasateam\Laravelapistone\Controllers\ShopProductSpecController;
use Wasateam\Laravelapistone\Controllers\ShopProductSpecSettingController;
use Wasateam\Laravelapistone\Controllers\ShopProductSpecSettingItemController;
use Wasateam\Laravelapistone\Controllers\ShopReturnRecordController;
use Wasateam\Laravelapistone\Controllers\ShopShipAreaSettingController;
use Wasateam\Laravelapistone\Controllers\ShopShipTimeSettingController;
use Wasateam\Laravelapistone\Controllers\ShopSubclassController;
use Wasateam\Laravelapistone\Controllers\SnappyController;
use Wasateam\Laravelapistone\Controllers\SocialiteController;
use Wasateam\Laravelapistone\Controllers\SocialiteFacebookAccountController;
use Wasateam\Laravelapistone\Controllers\SocialiteGoogleAccountController;
use Wasateam\Laravelapistone\Controllers\SocialiteLineAccountController;
use Wasateam\Laravelapistone\Controllers\SystemClassController;
use Wasateam\Laravelapistone\Controllers\SystemSubclassController;
use Wasateam\Laravelapistone\Controllers\TagController;
use Wasateam\Laravelapistone\Controllers\TermsController;
use Wasateam\Laravelapistone\Controllers\TulpaCrossItemController;
use Wasateam\Laravelapistone\Controllers\TulpaPageController;
use Wasateam\Laravelapistone\Controllers\TulpaPageTemplateController;
use Wasateam\Laravelapistone\Controllers\TulpaSectionController;
use Wasateam\Laravelapistone\Controllers\UserAddressController;
use Wasateam\Laravelapistone\Controllers\UserAppInfoController;
use Wasateam\Laravelapistone\Controllers\UserController;
use Wasateam\Laravelapistone\Controllers\UserDeviceController;
use Wasateam\Laravelapistone\Controllers\UserDeviceModifyRecordController;
use Wasateam\Laravelapistone\Controllers\UserDeviceTokenController;
use Wasateam\Laravelapistone\Controllers\UserInviteController;
use Wasateam\Laravelapistone\Controllers\UserServicePlanController;
use Wasateam\Laravelapistone\Controllers\UserServicePlanItemController;
use Wasateam\Laravelapistone\Controllers\UserServicePlanRecordController;
use Wasateam\Laravelapistone\Controllers\WebLogController;
use Wasateam\Laravelapistone\Controllers\WsBlogClassController;
use Wasateam\Laravelapistone\Controllers\WsBlogController;
use Wasateam\Laravelapistone\Controllers\XcMilestoneController;
use Wasateam\Laravelapistone\Controllers\XcProjectController;
use Wasateam\Laravelapistone\Controllers\XcTaskController;
use Wasateam\Laravelapistone\Controllers\XcTaskTemplateController;
use Wasateam\Laravelapistone\Controllers\XcWorkTypeController;
use Wasateam\Laravelapistone\Controllers\LotteryParticipantController;
use Wasateam\Laravelapistone\Controllers\LotteryPrizeController;
use Wasateam\Laravelapistone\Controllers\LotteryWinningRecordController;

class RoutesHelper
{
  public static function get_resource_scope_routes($controller, $model_name, $scopes)
  {
    if (in_array('read', $scopes)) {
      Route::group([
        "middleware" => ["wasascopes:{$model_name}-read"],
      ], function () use ($model_name, $controller) {
        Route::get($model_name, [$controller, 'index']);
        Route::get("{$model_name}/{id}", [$controller, 'show']);
      });
    }
    if (in_array('edit', $scopes)) {
      Route::group([
        "middleware" => ["wasascopes:{$model_name}-edit"],
      ], function () use ($model_name, $controller) {
        Route::post($model_name, [$controller, 'store']);
        Route::patch("{$model_name}/{id}", [$controller, 'update']);
        Route::delete($model_name, [$controller, 'destroy']);
      });
    }
  }

  public static function cms_routes()
  {

    # Auth
    \Wasateam\Laravelapistone\Helpers\RoutesHelper::auth_routes([
      "signin",
      "signout",
      "userget",
      "userpatch",
      "avatarpatch",
    ]);

    # XcWorkType
    if (config('stone.xc_work_type')) {
      Route::group([
        "middleware" => ["wasascopes:xc_work_type-read"],
      ], function () {
        Route::get("xc_work_type", [XcWorkTypeController::class, 'index']);
        Route::get("xc_work_type/{id}", [XcWorkTypeController::class, 'show']);
      });
      Route::group([
        "middleware" => ["wasascopes:xc_work_type-edit"],
      ], function () {
        Route::post("xc_work_type", [XcWorkTypeController::class, 'store']);
        Route::patch("xc_work_type/{id}", [XcWorkTypeController::class, 'update']);
        Route::delete("xc_work_type", [XcWorkTypeController::class, 'destroy']);
      });
    }

    # XcTask
    if (config('stone.xc_task')) {

      Route::group([
        "middleware" => ["wasascopes:xc_task_template-read"],
      ], function () {
        Route::get("xc_task_template", [XcTaskTemplateController::class, 'index']);
        Route::get("xc_task_template/{id}", [XcTaskTemplateController::class, 'show']);
      });
      Route::group([
        "middleware" => ["wasascopes:xc_task_template-edit"],
      ], function () {
        Route::post("xc_task_template", [XcTaskTemplateController::class, 'store']);
        Route::patch("xc_task_template/{id}", [XcTaskTemplateController::class, 'update']);
        Route::delete("xc_task_template", [XcTaskTemplateController::class, 'destroy']);
      });

      Route::group([
        "middleware" => ["wasascopes:xc_task-read,xc_task-read-my,xc_task-read-my-xc_project,xc_task-read-my-xc_work_type"],
      ], function () {
        Route::get("xc_task", [XcTaskController::class, 'index']);
        Route::get("xc_task/{id}", [XcTaskController::class, 'show']);
      });
      Route::group([
        "middleware" => ["wasascopes:xc_task-edit,xc_task-edit-my,xc_task-edit-my-xc_project,xc_task-edit-my-xc_work_type"],
      ], function () {
        Route::post("xc_task", [XcTaskController::class, 'store']);
        Route::patch("xc_task/{id}", [XcTaskController::class, 'update']);
        Route::delete("xc_task", [XcTaskController::class, 'destroy']);
      });

      // self::get_resource_scope_routes(
      //   XcTaskTemplateController::class,
      //   'xc_task_template',
      //   ['read', 'edit']
      // );
      // self::get_resource_scope_routes(
      //   XcTaskController::class,
      //   'xc_task',
      //   ['read', 'edit']
      // );
      // Route::group([
      //   "middleware" => ["wasascopes:read-my"],
      // ], function () use ($model_name, $controller) {
      //   Route::get("{$model_name}/my", [$controller, 'index_my']);
      //   Route::get("{$model_name}/my/{id}", [$controller, 'show_my']);
      // });
      // Route::group([
      //   "middleware" => ["wasascopes:edit-my"],
      // ], function () use ($model_name, $controller) {
      //   Route::post("{$model_name}/my", [$controller, 'store_my']);
      //   Route::patch("{$model_name}/my/{id}", [$controller, 'update_my']);
      //   Route::delete("{$model_name}/my", [$controller, 'destroy_my']);
      // });
      // personal
      // admin_group
    }

    # XcMilestone
    if (config('stone.xc_milestone')) {
      self::get_resource_scope_routes(
        XcMilestoneController::class,
        'xc_milestone',
        ['read', 'edit']
      );
    }

    # XcProject
    if (config('stone.xc_project')) {
      self::get_resource_scope_routes(
        XcProjectController::class,
        'xc_project',
        ['read', 'edit']
      );
    }
  }

  public static function get_all_routes_name()
  {
    $routeCollection = Route::getRoutes();
    $routes          = [];
    foreach ($routeCollection as $key => $route) {
      $routes[] = $route->getAction();
    }
    return $routes;
  }

  # Auth 相關 (CMS, Webapi皆可用)
  public static function auth_routes()
  {

    if (config('stone.auth')) {
      $model_name = config('stone.auth.model_name');
      $auth_scope = config('stone.auth.auth_scope');
      Route::group([
        'prefix' => 'auth',
      ], function () use ($model_name, $auth_scope) {
        Route::post('/signin', [AuthController::class, 'signin'])->middleware('throttle:5,3');;
        if (config('stone.auth.signup')) {
          Route::post('/signup', [AuthController::class, 'signup']);
        }
        if (config('stone.auth.verify')) {
          if (config('stone.auth.verify.email')) {
            Route::post('/email/verify/resend', [AuthController::class, 'email_verify_resend']);
            Route::group([
              "middleware" => ["signed"],
            ], function () {
              Route::post('/email/verify/{user_id}', [AuthController::class, 'email_verify'])->name('email_verify');
            });
          }
        }

        // $auth_middlewares = ["auth:{$model_name}", "scopes:{$auth_scope}"];
        // if (config('stone.auth.verify')) {
        //   if (config('stone.auth.verify.email')) {
        //     $auth_middlewares[] = 'verified';
        //   }
        // }

        Route::group([
          "middleware" => ["auth:{$model_name}", "scopes:{$auth_scope}", "verified"],
          // "middleware" => $auth_middlewares,
        ], function () {
          Route::post('/signout', [AuthController::class, 'signout']);
          Route::get('/user', [AuthController::class, 'user']);
          Route::patch('/user', [AuthController::class, 'update']);
          if (config('stone.auth.passwordpatch')) {
            Route::patch('/user/password', [AuthController::class, 'password_update']);
          }
          // if (in_array('avatarpatch', $routes)) {
          //   if (env('SIGNED_URL_MODE') == 'gcs') {
          //     Route::get("/avatar/upload_url/{filename}", [AuthController::class, 'get_avatar_upload_url']);
          //   } else {
          //     Route::put("/avatar/{filename}", [AuthController::class, 'avatar_upload']);
          //   }
          // }
        });

        if (config('stone.auth.forgetpassword')) {
          Route::post("/forgetpassword/request", [AuthController::class, 'forget_password_request']);
          Route::group([
            "middleware" => ["signed"],
          ], function () {
            Route::post('/forgetpassword/patch/{user_id}', [AuthController::class, 'forget_password_patch'])->name('forget_password_patch');
          });
        }
      });
    }
  }

  # CMS 系統管理員
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
    if (config('stone.log')) {
      Route::resource('cms_log', CmsLogController::class)->only([
        'index',
        'show',
      ])->shallow();
    }

    # Locale
    Route::resource('locale', LocaleController::class)->only([
      'index', 'show', 'store', 'update', 'destroy',
    ])->shallow();
  }

  # CMS 管理員
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
      # 黑名單
      if (config('stone.user.is_bad')) {
        Route::post('user/{id}/bad', [UserController::class, 'bad']);
        Route::post('user/{id}/notbad', [UserController::class, 'notbad']);
      }
      # Reset Passsword Mail
      if (config('stone.user.reset_password_mail')) {
        Route::post('user/{id}/reset_password_mail', [UserController::class, 'reset_password_mail']);
      }
      # Export
      if (config('stone.user.export')) {
        Route::get('user/export/excel/signedurl', [UserController::class, 'export_excel_signedurl']);
      }
      # UserAddress
      if (config('stone.user.address')) {
        Route::resource('user_address', UserAddressController::class)->only([
          'index',
          'show',
          'store',
          'update',
          'destroy',
        ])->shallow();
      }
      # UserDevice
      if (config('stone.user.device')) {
        Route::resource('user_device', UserDeviceController::class)->only([
          'index', 'show', 'store', 'update', 'destroy',
        ])->shallow();
        Route::resource('user_device_modify_record', UserDeviceModifyRecordController::class)->only([
          'index', 'show', 'store', 'update', 'destroy',
        ])->shallow();
      }
      # UserInvite
      if (config('stone.user.invite')) {

        # Privacy Terms
        if (config('stone.user.invite.general')) {
          Route::get('general_user_invite', [GeneralUserInviteController::class, 'show']);
          Route::patch('general_user_invite', [GeneralUserInviteController::class, 'update']);
          Route::get('general_user_invite', [GeneralUserInviteController::class, 'show']);
          Route::patch('general_user_invite', [GeneralUserInviteController::class, 'update']);
        }
      }
    }

    # Socialite
    if (config('stone.socialite')) {
      if (config('stone.socialite.google')) {
        Route::resource('socialite_google_account', SocialiteGoogleAccountController::class)->only([
          'index', 'show', 'store', 'update', 'destroy',
        ])->shallow();
      }
      if (config('stone.socialite.facebook')) {
        Route::resource('socialite_facebook_account', SocialiteFacebookAccountController::class)->only([
          'index', 'show', 'store', 'update', 'destroy',
        ])->shallow();
      }
      if (config('stone.socialite.line')) {
        Route::resource('socialite_line_account', SocialiteLineAccountController::class)->only([
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
      Route::resource('ws_blog_class', WsBlogClassController::class)->only([
        'index', 'show', 'store', 'update', 'destroy',
      ])->shallow();
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
      Route::resource('contact_request_notify_mail', ContactRequestNotifyMailController::class)->only([
        'index', 'show', 'store', 'update', 'destroy',
      ])->shallow();
      if (config('stone.contact_request.auto_reply')) {
        Route::get('contact_request_auto_reply', [ContactRequestAutoReplyController::class, 'show']);
        Route::patch('contact_request_auto_reply', [ContactRequestAutoReplyController::class, 'update']);
      }
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
      # Export
      if (config('stone.appointment.export')) {
        Route::get('appointment/export/excel/signedurl', [AppointmentController::class, 'export_excel_signedurl']);
      }
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
      Route::resource('user_service_plan_record', UserServicePlanRecordController::class)->only([
        'index', 'show',
      ])->shallow();
      Route::post('user_service_plan_item/{id}/remain_count_deduct', [UserServicePlanItemController::class, 'remain_count_deduct']);
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
    if (config('stone.notification')) {
      Route::resource('notification', NotificationController::class)->only([
        'index',
        'show',
        'destroy',
      ])->shallow();
    }

    # Shop
    if (config('stone.shop')) {
      if (config('stone.shop.current')) {
        Route::resource('shop_product', ShopProductController::class)->only([
          'index', 'show', 'store', 'update', 'destroy',
        ])->shallow();
      }
      Route::get('shop_product/export/excel/signedurl', [ShopProductController::class, 'export_excel_signedurl']);
      // if (config('stone.shop.pre_order')) {
      //   Route::resource('shop_product_pre_order', ShopProductPreOrderController::class)->only([
      //     'index', 'show', 'store', 'update', 'destroy',
      //   ])->shallow();
      // }
      Route::resource('shop_product_cover_frame', ShopProductCoverFrameController::class)->only([
        'index', 'show', 'store', 'update', 'destroy',
      ])->shallow();
      Route::resource('shop_product_expect_ship', ShopProductExpectShipController::class)->only([
        'index', 'show', 'store', 'update', 'destroy',
      ])->shallow();
      Route::get('/shop_class/order', [ShopClassController::class, 'order_get']);
      Route::patch('/shop_class/order', [ShopClassController::class, 'order_patch']);
      Route::resource('shop_class', ShopClassController::class)->only([
        'index', 'show', 'store', 'update', 'destroy',
      ])->shallow();
      Route::resource('shop_subclass', ShopSubclassController::class)->only([
        'index', 'show', 'store', 'update', 'destroy',
      ])->shallow();
      Route::get('shop_subclass/{id}/shop_product/order', [ShopSubclassController::class, 'shop_product_order_get']);
      Route::patch('shop_subclass/{id}/shop_product/order', [ShopSubclassController::class, 'shop_product_order_patch']);
      Route::resource('shop_ship_area_setting', ShopShipAreaSettingController::class)->only([
        'index', 'show', 'store', 'update', 'destroy',
      ])->shallow();
      Route::resource('shop_ship_time_setting', ShopShipTimeSettingController::class)->only([
        'index', 'show', 'store', 'update', 'destroy',
      ])->shallow();
      # Shop Order
      Route::patch('shop_order/batch', [ShopOrderController::class, 'batch_update']);
      Route::post('shop_order/{id}/cancel', [ShopOrderController::class, 'cancel']);
      Route::post('shop_order/{id}/return/cancel', [ShopOrderController::class, 'return_cancel']);
      Route::resource('shop_order', ShopOrderController::class)->only([
        'index', 'show', 'store', 'update', 'destroy',
      ])->shallow();
      if (config('stone.shop.order_export')) {
        Route::get('shop_order/export/pdf/signedurl', [ShopOrderController::class, 'export_pdf_signedurl']);
      }
      Route::get('shop_order/export/excel/signedurl', [ShopOrderController::class, 'export_excel_signedurl']);
      // Route::post('shop_order/{shop_order_id}/invoice', [ShopOrderController::class, 'create_invice']);
      Route::post('shop_order/{shop_order_id}/re_create_invice', [ShopOrderController::class, 're_create_invice']);
      // Route::post('shop_order/{shop_order_id}/re_create', [ShopOrderController::class, 're_create']);
      # Shop Order Product
      Route::resource('shop_order_shop_product', ShopOrderShopProductController::class)->only([
        'index', 'show', 'store', 'update', 'destroy',
      ])->shallow();
      # Shop Order Shop Product Spec
      Route::resource('shop_order_shop_product_spec', ShopOrderShopProductSpecController::class)->only([
        'index', 'show', 'store', 'update', 'destroy',
      ])->shallow();
      # Shop Order Shop Product Spec Setting
      Route::resource('shop_ord_shop_pro_spec_set', ShopOrderShopProductSpecSettingController::class)->only([
        'index', 'show', 'store', 'update', 'destroy',
      ])->shallow();
      # Shop Order Shop Product Spec Setting Item
      Route::resource('shop_ord_shop_pro_spec_set_item', ShopOrderShopProductSpecSettingItemController::class)->only([
        'index', 'show', 'store', 'update', 'destroy',
      ])->shallow();
      # Shop Cart
      Route::resource('shop_cart', ShopCartController::class)->only([
        'index', 'show', 'store', 'update', 'destroy',
      ])->shallow();
      # Shop Cart Product
      Route::resource('shop_cart_product', ShopCartProductController::class)->only([
        'index', 'show', 'store', 'update', 'destroy',
      ])->shallow();
      # Shop Return Record
      Route::resource('shop_return_record', ShopReturnRecordController::class)->only([
        'index', 'show', 'store', 'update', 'destroy',
      ])->shallow();
      Route::post('shop_return_record/return/all', [ShopReturnRecordController::class, 'return_all']);
      # Shop Free Shipping
      Route::resource('shop_free_shipping', ShopFreeShippingController::class)->only([
        'index', 'show', 'store', 'update', 'destroy',
      ])->shallow();
      # Shop Product Import Record
      Route::resource('shop_product_import_record', ShopProductImportRecordController::class)->only([
        'index', 'show',
      ])->shallow();
      Route::post('shop_product/import/excel', [ShopProductController::class, 'import_excel']);
      # Shop Campaign
      Route::resource('shop_campaign', ShopCampaignController::class)->only([
        'index', 'show', 'store', 'update', 'destroy',
      ])->shallow();
      # Shop Campaign Shop Order
      Route::resource('shop_campaign_shop_order', ShopCampaignShopOrderController::class)->only([
        'index', 'show', 'store', 'update', 'destroy',
      ])->shallow();
      # Shop Product Spec
      Route::resource('shop_product_spec', ShopProductSpecController::class)->only([
        'index', 'show', 'store', 'update', 'destroy',
      ])->shallow();
      # Shop Product Spec Setting
      Route::resource('shop_product_spec_setting', ShopProductSpecSettingController::class)->only([
        'index', 'show', 'store', 'update', 'destroy',
      ])->shallow();
      # Shop Product Spec
      Route::resource('shop_product_spec_setting_item', ShopProductSpecSettingItemController::class)->only([
        'index', 'show', 'store', 'update', 'destroy',
      ])->shallow();

    }

    # Shop Notice
    if (config('stone.shop.notice')) {
      Route::resource('shop_notice', ShopNoticeController::class)->only([
        'index', 'show', 'store', 'update', 'destroy',
      ])->shallow();
      Route::get('/shop_notice/image/upload_url', [ShopNoticeController::class, 'image_get_upload_url']);
      Route::resource('shop_notice_class', ShopNoticeClassController::class)->only([
        'index', 'show', 'store', 'update', 'destroy',
      ])->shallow();
    }

    # News
    if (config('stone.news')) {
      Route::resource('news', NewsController::class)->only([
        'index', 'show', 'store', 'update', 'destroy',
      ])->shallow();
      Route::get('/news/image/upload_url', [NewsController::class, 'image_get_upload_url']);
    }

    # FeaturedClass
    if (config('stone.featured_class')) {
      Route::resource('featured_class', FeaturedClassController::class)->only([
        'index', 'show', 'store', 'update', 'destroy',
      ])->shallow();
    }

    # Privacy Terms
    if (config('stone.privacy_terms')) {
      Route::get('privacy', [PrivacyController::class, 'show']);
      Route::patch('privacy', [PrivacyController::class, 'update']);
      Route::get('terms', [TermsController::class, 'show']);
      Route::patch('terms', [TermsController::class, 'update']);
    }

    # Web Log
    if (config('stone.web_log')) {
      Route::resource('web_log', WebLogController::class)->only([
        'index',
        'show',
      ])->shallow();
    }

    # Finance
    if (config('stone.finance')) {
      if (config('stone.finance.payment_request')) {
        Route::resource('admin_finance_payment_request', AdminFinancePaymentRequestController::class)->only([
          'index', 'show', 'store', 'update', 'destroy',
        ])->shallow();
      }
    }

    # News Banner
    if (config('stone.news_banner')) {
      Route::get('/news_banner/order', [NewsBannerController::class, 'order_get']);
      Route::patch('/news_banner/order', [NewsBannerController::class, 'order_patch']);
      Route::resource('news_banner', NewsBannerController::class)->only([
        'index', 'show', 'store', 'update', 'destroy',
      ])->shallow();
      Route::resource('news_banner_group', NewsBannerGroupController::class)->only([
        'index', 'show', 'store', 'update', 'destroy',
      ])->shallow();
      Route::get('news_banner_group/{id}/news_banner/order', [NewsBannerGroupController::class, 'news_banner_order_get']);
      Route::patch('news_banner_group/{id}/news_banner/order', [NewsBannerGroupController::class, 'news_banner_order_patch']);
    }

    # Page Setting
    if (config('stone.page_setting')) {
      Route::resource('page_setting', PageSettingController::class)->only([
        'index', 'show', 'store', 'update', 'destroy',
      ])->shallow();
    }

    # Page Setting
    if (config('stone.page_cover')) {
      Route::resource('page_cover', PageCoverController::class)->only([
        'index', 'show', 'store', 'update', 'destroy',
      ])->shallow();
    }

    # Acumatica
    if (config('stone.acumatica')) {
      Route::resource('acumatica_app', AcumaticaAppController::class)->only([
        'index', 'show', 'store', 'update', 'destroy',
      ])->shallow();
    }

    # BonusPoint
    if (config('stone.bonus_point')) {
      # BonusPointRecord
      Route::resource('bonus_point_record', BonusPointRecordController::class)->only([
        'index', 'show', 'store', 'update', 'destroy',
      ])->shallow();
    }

    # Lottery
    if (config('stone.lottery')) {

      Route::resource('lottery_participant', LotteryParticipantController::class)->only([
        'index', 'show', 'store', 'update', 'destroy',
      ])->shallow();

      Route::resource('lottery_prize', LotteryPrizeController::class)->only([
        'index', 'show', 'store', 'update', 'destroy',
      ])->shallow();

      Route::resource('lottery_winning_record', LotteryWinningRecordController::class)->only([
        'index', 'show', 'store', 'update', 'destroy',
      ])->shallow();
    }
  }

  # CMS 公開
  public static function cms_public_routes()
  {
    if (config('stone.pin_card')) {
      Route::group([
        "middleware" => ["signed"],
      ], function () {
        Route::get('pin_card/export/excel', [PinCardController::class, 'export_excel'])->name('pin_card_export_excel');
      });
    }

    if (config('stone.user')) {
      Route::group([
        "middleware" => ["signed"],
      ], function () {
        if (config('stone.user.export')) {
          Route::get('user/export/excel', [UserController::class, 'export_excel'])->name('user_export_excel');
        }

      });
    }

    if (config('stone.appointment')) {
      Route::group([
        "middleware" => ["signed"],
      ], function () {
        if (config('stone.appointment.export')) {
          Route::get('appointment/export/excel', [AppointmentController::class, 'export_excel'])->name('appointment_export_excel');
        }
      });
    }

    if (config('stone.shop')) {
      Route::group([
        "middleware" => ["signed"],
      ], function () {
        if (config('stone.shop.order_export')) {
          Route::get('shop_order/export/pdf', [ShopOrderController::class, 'export_pdf'])->name('shop_order_export_pdf');
        }
        Route::get('shop_order/export/excel', [ShopOrderController::class, 'export_excel'])->name('shop_order_export_excel');
        Route::get('shop_product/export/excel', [ShopProductController::class, 'export_excel'])->name('shop_product_export_excel');
      });
    }
  }

  # Web 需登入
  public static function webapi_auth_routes()
  {
    # Pincard
    if (config('stone.pin_card')) {
      Route::post('pin_card/register', [PinCardController::class, 'register']);
    }

    # Appointment
    if (config('stone.appointment')) {
      Route::resource('appointment', AppointmentController::class)->only([
        'index',
        'show',
        'store',
        'update',
      ])->shallow();
    }

    # Third Party Payment
    if (config('stone.third_party_payment')) {
      # Ecpay Inpay
      if (config('stone.third_party_payment.ecpay_inpay')) {
        Route::post('ecpay/inpay/merchant_init', [EcpayController::class, 'get_inpay_merchant_init']);
        Route::post('ecpay/inpay/create_payment', [EcpayController::class, 'inpay_create_payment']);
      }

      # LINE Pay
      if (config('stone.third_party_payment.line_pay')) {
        Route::post('line_pay/payment/init', [LinePayController::class, 'payment_init']);
        Route::post('line_pay/payment/confirm', [LinePayController::class, 'payment_confirm']);
        Route::post('line_pay/payment/cancel', [LinePayController::class, 'payment_cancel']);
      }
    }

    # UserDevice
    if (config('stone.user.device')) {
      Route::get('user_device/info/binding_status', [UserDeviceController::class, 'get_info_user_binding_status']);
      Route::resource('user_device', UserDeviceController::class)->only([
        'index', 'show',
      ])->shallow();
      Route::post('user_device/register', [UserDeviceController::class, 'register']);
      Route::post('user_device/active/{id}', [UserDeviceController::class, 'active']);
      Route::post('user_device/deactive/{id}', [UserDeviceController::class, 'deactive']);
    }

    # UserServicePlan
    if (config('stone.service_plan')) {
      Route::get('user_service_plan/current', [UserServicePlanController::class, 'get_current']);
      Route::resource('user_service_plan', UserServicePlanController::class)->only([
        'index', 'show',
      ])->shallow();
      if (config('stone.service_plan.using_record')) {
        if (config('stone.service_plan.using_record.from') == 'acumatica') {
          Route::resource('service_plan_using_record', ServicePlanUsingRecordController::class)->only([
            'index',
          ])->shallow();
        }
      }
    }

    # Shop
    if (config('stone.shop')) {
      # Shop Order
      Route::resource('shop_order', ShopOrderController::class)->only([
        'store',
      ])->shallow();
    }

    if (config('stone.user.invite')) {
      # Invite Check
      Route::post('user_invite/check', [UserInviteController::class, 'check']);
    }
  }

  # Web 需符合對應使用者之
  public static function webapi_isuser_routes()
  {

    # UserServicePlan
    if (config('stone.service_plan')) {
      // Route::resource('user_service_plan', UserServicePlanController::class)->only([
      //   'index',
      //   'show',
      // ])->shallow();
      // Route::resource('user_service_plan_item', UserServicePlanItemController::class)->only([
      //   'index',
      //   'show',
      // ])->shallow();
      // Route::resource('user_service_plan_record', UserServicePlanRecordController::class)->only([
      //   'index', 'show',
      // ])->shallow();
    }

    # User
    if (config('stone.user')) {

      # ShopCampaign
      Route::post('/shop_campaign/today/discount_code', [ShopCampaignController::class, 'get_today_discount_code']);

      # UserAddress
      if (config('stone.user.address')) {
        Route::resource('user_address', UserAddressController::class)->only([
          'index',
          'show',
          'store',
          'update',
          'destroy',
        ])->shallow();
      }
    }

    # Appointment
    if (config('stone.appointment')) {
      Route::resource('appointment', AppointmentController::class)->only([
        'index', 'show', 'store',
      ])->shallow();
    }

    # Shop
    if (config('stone.shop')) {
      # Shop Order
      Route::post('shop_order/calc', [ShopOrderController::class, 'calc']);
      Route::resource('shop_order', ShopOrderController::class)->only([
        'index',
        'show',
      ])->shallow();
    }
  }

  # Web 不需登入
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
      Route::resource('ws_blog_class', WsBlogClassController::class)->only([
        'index',
        'show',
      ])->shallow();
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

    # Area
    if (config('stone.area')) {
      Route::resource('area', AreaController::class)->only([
        'index',
        'show',
      ])->shallow();
      Route::resource('area_section', AreaSectionController::class)->only([
        'index',
        'show',
      ])->shallow();
    }

    # SystemClass
    if (config('stone.system_class')) {
      Route::resource('area.system_class', SystemClassController::class)->only([
        'show',
      ])->shallow();
      Route::get('area/{area}/system_class', [SystemClassController::class, 'index_with_area']);
      Route::resource('system_class.system_subclass', SystemSubclassController::class)->only([
        'index',
        'show',
      ])->shallow();
    }

    # UserDeviceToken
    if (config('stone.user_device_token')) {
      Route::post('device_token', [UserDeviceTokenController::class, 'active']);
      Route::delete('device_token', [UserDeviceTokenController::class, 'deactive']);
    }

    # Notification
    if (config('stone.notification')) {
      Route::get('notification', [NotificationController::class, 'user_index']);
      Route::get('notification/unread', [NotificationController::class, 'user_index_unread']);
      Route::post('notification/{id}/read', [NotificationController::class, 'user_read']);
      Route::post('notification/readall', [NotificationController::class, 'user_readall']);
    }

    # Socialite
    Route::group([
      'prefix' => 'auth',
    ], function () {
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
    });

    # Shop
    if (config('stone.shop')) {
      if (config('stone.shop.current')) {
        Route::resource('shop_product', ShopProductController::class)->only([
          'index', 'show',
        ])->shallow();
      }
      // if (config('stone.shop.pre_order')) {
      //   Route::resource('shop_product_pre_order', ShopProductPreOrderController::class)->only([
      //     'index', 'show',
      //   ])->shallow();
      // }
      if (config('stone.shop.favorite')) {
        Route::post('/auth/shop_product/{shop_product_id}/collect', [ShopProductController::class, 'collect_shop_product']);
        Route::post('/auth/shop_product/{shop_product_id}/uncollect', [ShopProductController::class, 'uncollect_shop_procut']);
        Route::get('/auth/collected_shop_product/index', [ShopProductController::class, 'collected_shop_product_index']);
        Route::get('/auth/collected_shop_product/ids', [ShopProductController::class, 'collected_shop_product_ids']);
      }
      Route::resource('shop_class', ShopClassController::class)->only([
        'index', 'show',
      ])->shallow();
      Route::resource('shop_subclass', ShopSubclassController::class)->only([
        'index', 'show',
      ])->shallow();
      Route::resource('shop_ship_area_setting', ShopShipAreaSettingController::class)->only([
        'index', 'show',
      ])->shallow();
      Route::resource('shop_ship_time_setting', ShopShipTimeSettingController::class)->only([
        'index', 'show',
      ])->shallow();
      # Shop Cart
      Route::get('/auth/shop_cart', [ShopCartController::class, 'auth_cart']);
      # Shop Cart Product
      Route::resource('shop_cart_product', ShopCartProductController::class)->only([
        'index',
        'store',
        'delete',
      ])->shallow();
      Route::post('/auth/shop_cart_product/{shop_cart_product_id}/update', [ShopCartProductController::class, 'update_auth_cart_product']);
      Route::post('/shop_cart_product/{shop_cart_product_id}', [ShopCartProductController::class, 'disabled']);
      # Shop Return Record
      Route::resource('shop_return_record', ShopReturnRecordController::class)->only([
        'index', 'show',
      ])->shallow();
      # Shop Free Shipping
      Route::resource('shop_free_shipping', ShopFreeShippingController::class)->only([
        'index', 'show',
      ])->shallow();
      # Shop Campaign
      Route::resource('shop_campaign', ShopCampaignController::class)->only([
        'index', 'show',
      ])->shallow();
      Route::get('/shop_campaign/today/index', [ShopCampaignController::class, 'today_index']);
    }

    # Shop Notice
    if (config('stone.shop.notice')) {
      Route::resource('shop_notice', ShopNoticeController::class)->only([
        'index',
        'show',
      ])->shallow();
      Route::get('shop_notice/{id}/read', [ShopNoticeController::class, 'read']);
      Route::resource('shop_notice_class', ShopNoticeController::class)->only([
        'index',
        'show',
      ])->shallow();
    }

    # News
    if (config('stone.news')) {
      Route::resource('news', NewsController::class)->only([
        'index',
        'show',
      ])->shallow();
      Route::get('news/{id}/read', [NewsController::class, 'read']);
    }

    # FeaturedClass
    if (config('stone.featured_class')) {
      Route::resource('featured_class', FeaturedClassController::class)->only([
        'index', 'show',
      ])->shallow();
    }

    if (config('stone.file_upload') == 'laravel_signed') {
      Route::group([
        "middleware" => ["signed"],
      ], function () {
        Route::get('/{model}/{model_id}/{type}/{repo}/{name}', '\Wasateam\Laravelapistone\Controllers\FileController@file_idmatch')->name('file_idmatch');
        Route::get('/{model}/{type}/{repo}/{name}', '\Wasateam\Laravelapistone\Controllers\FileController@file_general')->name('file_general');
        Route::get('/{parent}/{parent_id}/{model}/{type}/{repo}/{name}', '\Wasateam\Laravelapistone\Controllers\FileController@file_parentidmatch')->name('file_parentidmatch');
      });
    }

    # Privacy Terms
    if (config('stone.privacy_terms')) {
      Route::get('privacy', [PrivacyController::class, 'show']);
      Route::get('terms', [TermsController::class, 'show']);
    }

    # Ecpay
    if (config('stone.third_party_payment')) {
      if (config('stone.third_party_payment.ecpay_inpay')) {
        Route::post('callback/ecpay/inpay/order', [EcpayController::class, 'callback_ecpay_inpay_order']);
      }
    }

    # Invoice
    if (config('stone.invoice')) {
      Route::post('callback/invoice/notify', [EcpayController::class, 'callback_invoice_notify']);
    }

    # ContactRequest
    if (config('stone.contact_request')) {
      Route::resource('contact_request', ContactRequestController::class)->only([
        'store',
      ])->shallow();
      if (config('stone.contact_request.auto_reply')) {
        Route::get('contact_request_auto_reply', [ContactRequestAutoReplyController::class, 'show']);
      }
    }

    # News Banner
    if (config('stone.news_banner')) {
      Route::resource('news_banner', NewsBannerController::class)->only([
        'index', 'show',
      ])->shallow();
      Route::resource('news_banner_group', NewsBannerGroupController::class)->only([
        'index', 'show',
      ])->shallow();
    }

    # Page Setting
    if (config('stone.page_setting')) {
      Route::get('page_setting', [PageSettingController::class, 'index']);
      Route::get('page_setting/page', [PageSettingController::class, 'show']);
    }

    # Page Setting
    if (config('stone.page_cover')) {
      Route::resource('page_cover', PageCoverController::class)->only([
        'index', 'show',
      ])->shallow();
    }

    # BonusPoint
    if (config('stone.bonus_point')) {
      # BonusPointRecord
      Route::resource('bonus_point_record', BonusPointRecordController::class)->only([
        'index',
        'show',
      ])->shallow();
      // Route::get('bonus_point_record/auth/index', [BonusPointRecordController::class, 'auth_index']);
    }
  }

  # 以下準備拉進垃圾車
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
