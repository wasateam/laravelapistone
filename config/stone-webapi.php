<?php

return [
  'mode'                => 'webapi',
  'storage'             => [
    'service' => 'gcs', # gcs, s3, local
    'gcs'     => [
      'bucket' => env('GOOGLE_CLOUD_STORAGE_BUCKET', null),
    ],
    'acl'     => true,
  ],
  'mail'                => [
    'service'    => env('MAIL_MAILER'), // gmail, surenotify , smtp
    'api_key'    => env('MAIL_API_KEY'),
    'api_domain' => env('MAIL_API_DOMAIN'),
    'test_mail'  => env('MAIL_TEST_MAIL', 'wasalearn@gmail.com'),
  ],
  'app_url'             => env('APP_URL'),
  'web_url'             => env('WEB_URL'),
  // Auth
  'auth'                => [
    'signup'          => true,
    'passwordpatch'   => true,
    'forgetpassword'  => true,
    'model_name'      => 'user',
    'model'           => '\Wasateam\Laravelapistone\Models\User',
    'resource'        => '\Wasateam\Laravelapistone\Resources\User',
    'auth_scope'      => 'user',
    'default_scopes'  => [
      'user',
    ],
    'active_check'    => true,
    'verify'          => [
      'email' => true,
    ],
    'forget_password' => true,
    // 'signup_complete_action' => '\App\Helpers\TestHelper',
  ],
  # Modules
  'log'                 => true,
  'post_encode'         => false,
  'tulpa'               => true,
  'socialite'           => [
    'disable_auto_verify' => true,
    'facebook'            => true,
    'google'              => true,
    'line'                => true,
  ],
  'user_device_token'   => true,
  'ws_blog'             => true,
  'tag'                 => true,
  'area'                => true,
  'system_class'        => true,
  'contact_request'     => [
    // 'notify_mail' => 'hello@wasateam.com',
    'fields'     => [
      'name'         => true,
      'email'        => true,
      'tel'          => true,
      'company_name' => true,
      'remark'       => true,
      'budget'       => false,
    ],
    'auto_reply' => true,
  ],
  'locale'              => false,
  'service_store'       => false,
  'notification'        => true,
  'appointment'         => [
    'notify' => [
      'email'         => true,
      'before_houres' => 1,
    ],
  ],
  'service_plan'        => [
    'using_record' => [
      'from' => 'acumatica',
    ],
  ],
  // 'pin_card'            => [
  //   'register_complete_action'=>
  // ],
  'calendar_highlight'  => false,
  'user'                => [
    'export'              => true,
    'is_bad'              => true,
    'bonus_points'        => true,
    'reset_password_mail' => true,
    'carriers'            => true,
    'subscribe'           => true,
    'address'             => [
      'delivery' => [
        'limit' => 3,
      ],
    ],
    'customer_id'         => [
      'logic'              => 'year-serial',
      'number_least_count' => 6,
      'prefix'             => "HC",
    ],
    'acumatica_id'        => true,
    'device'              => [
      'limit'                  => 2,
      'acumatica'              => true,
      'register_before_action' => '\App\Helpers\AppHelper',
      'active_before_action'   => '\App\Helpers\AppHelper',
      'deactive_before_action' => '\App\Helpers\AppHelper',
    ],
    'invite'              => [
      'general' => [
        // 'invited_shop_deduct_rate'     => 9,
        'invited_shop_deduct'          => 200,
        'invite_feedback_bonus_points' => 100,
      ],
      // 'each'    => true,
    ],
  ],
  'shop'                => [
    'uuid'              => true,
    'pre_order'         => true,
    'current'           => true,
    'custom_shop_order' => true,
    'notice'            => true,
    'freight_default'   => 100,
    'order_type'        => [
      'next_day'  => [
        'freight_default'        => 100,
        'has_shop_free_shipping' => true,
      ],
      'pre_order' => [
        'freight_separate' => true,
      ],
    ],
    'shop_campaign'     => [
      'types' => [ //活動類型分別設定
        'bonus_point_feedback' => [
          'feedback_after_invoice_days' => 3, //開立發票後多久發紅利
          'date_no_repeat'              => true, //日期是否重複
        ],
      ],
    ],
    //我的最愛
    'favorite'          => true,
  ],
  'file_upload'         => 'laravel_signed',
  'privacy_terms'       => true,
  'third_party_payment' => [
    'ecpay_inpay' => [
      'pay_way'                 => [
        'credit_card'             => true,
        'credit_card_installment' => true,
        'atm'                     => true,
        'supermarket_code'        => true,
        'supermarket_barcode'     => true,
      ],
      'insite_order_return_url' => env('APP_URL') . env('ECPAY_INSITE_ORDER_RETURN_URL', "/api/callback/ecpay/inpay/order"),
      'cardinfo'                => [
        'order_return_url'  => env('APP_URL') . env('ECPAY_CARDINFO_3DORDER_RETURN_URL', "/api/callback/ecpay/cardinfo/3dorder"),
        'period_return_url' => env('APP_URL') . env('ECPAY_CARDINFO_PERIOD_RETURN_URL', "/api/callback/ecpay/cardinfo/period"),
      ],
      'merchant_id'             => env('ECPAY_MERCHANT_ID', "2000132"),
      'uniform_no'              => env('ECPAY_UNIFORM_NO', "53538851"),
      'cms_account'             => env('ECPAY_CMS_ACCOUNT', "stagetest1234"),
      'cms_password'            => env('ECPAY_CMS_PASSWORD', "test1234"),
      'hash_key'                => env('ECPAY_HASH_KEY', "5294y06JbISpM5x9"),
      'hash_iv'                 => env('ECPAY_HASH_IV', "v77hoKGq4kWxNNIS"),
      'cms_link'                => env('ECPAY_CMS_LINK', "https://vendor-stage.ecpay.com.tw"),
      'card_no'                 => env('ECPAY_CARD_NO', "4311-9522-2222-2222"),
      'card_safe_no'            => env('ECPAY_CARD_SAFE_NO', "222"),
    ],
  ],
  'invoice'             => [
    'service'    => 'ecpay',
    'mode'       => env('ECPAY_MODE', 'dev'),
    'delay'      => 3,
    'notify_url' => env('APP_URL') . env('ECPAY_INVOICE_NOTIFY_URL', "/api/callback/invoice/notify"),
    'ecpay'      => [
      'merchant_id' => env('ECPAY_MERCHANT_ID', "2000132"),
    ],
  ],
  'test'                => [
    'receive_mail' => env('STONE_TEST_RECEIVE_MAIL', 'hello@wasateam.com'),
  ],
  'country_code'        => true,
  // multiple layers
  'featured_class'      => true,
  'acumatica'           => [
    'app_mode'      => true,
    'token_url'     => env('ACUMATICA_TOKEN_URL'),
    'api_url'       => env('ACUMATICA_API_URL'),
    'client_id'     => env('ACUMATICA_CLIENT_ID'),
    'client_secret' => env('ACUMATICA_CLIENT_SECRET'),
    'username'      => env('ACUMATICA_USERNAME'),
    'password'      => env('ACUMATICA_PASSWORD'),
  ],
  //banner
  'news_banner'         => true,
  'news'                => true,
  //頁面設定
  'page_setting'        => true,
  //頁面彈跳視窗
  'page_cover'          => true,
  'excute_class'        => true,
  //點數系統
  'bonus_point'         => true,
];
