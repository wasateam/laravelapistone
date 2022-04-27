<?php

return [
  'mode'               => 'cms',
  'schedule'           => [
    'log' => env('STONE_SCHEDULE_LOG', false),
  ],
  'queue'              => true,
  'timezone'           => 'Asia/Taipei',
  'migration'          => true,
  'storage'            => [
    'service' => 'gcs', # gcs, s3, local
    'gcs'     => [
      'bucket' => env('GOOGLE_CLOUD_STORAGE_BUCKET', null),
    ],
    'acl'     => true,
  ],
  'mail'               => [
    'service'    => 'gmail', # gmail, surenotify,smtp
    'api_key'    => env('MAIL_API_KEY'),
    'api_domain' => env('MAIL_API_DOMAIN'),
    'test_mail'  => env('MAIL_TEST_MAIL', 'wasalearn@gmail.com'),
  ],
  'app_url'            => env('APP_URL'),
  'web_url'            => env('WEB_URL'),
  'web_api_url'        => env('WEB_API_URL'),
  # Auth
  'auth'               => [
    'passwordpatch'  => true,
    'model_name'     => 'admin',
    'model'          => '\Wasateam\Laravelapistone\Models\Admin',
    'resource'       => '\Wasateam\Laravelapistone\Resources\Admin',
    'auth_scope'     => 'admin',
    'default_scopes' => [
      'boss',
      'admin',
    ],
    'active_check'   => true,
  ],
  # Modules
  'admin_blur'         => true,
  'admin_role'         => true,
  'admin_group'        => true,
  'admin_system_class' => true,
  'log'                => true,
  'post_encode'        => false,
  'pocket'             => true,
  'tulpa'              => true,
  'user'               => [
    'export'              => [
      'uuid'             => true,
      'customer_id'      => true,
      'name'             => true,
      'country'          => true,
      'address_mailing'  => true,
      'gender'           => true,
      'email'            => true,
      'tel'              => true,
      'birthday'         => true,
      'description'      => true,
      'created_at'       => true,
      'created_at_year'  => true,
      'created_at_month' => true,
      'created_at_day'   => true,
      'updated_at'       => true,
      'description'      => true,
      'is_bad'           => true,
      'bonus_points'     => true,
      'subscribe'        => true,
      'shop_sum'         => true,
      'shop_count'       => true,
      'invite_count'     => true,
    ],
    'is_bad'              => true,
    'bonus_points'        => true,
    'reset_password_mail' => true,
    'carriers'            => true,
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
      'acumatica' => true,
    ],
    'invite'              => [
      'general' => true,
      'each'    => true,
    ],
  ],
  'user_device_token'  => true,
  'socialite'          => [
    'facebook' => true,
    'google'   => true,
    'line'     => true,
  ],
  'web_log'            => true,
  'ws_blog'            => true,
  'tag'                => true,
  'area'               => true,
  'system_class'       => true,
  'contact_request'    => [
    'auto_reply' => true,
  ],
  'locale'             => [
    'default' => 'zh_tw',
  ],
  'notification'       => [
    'notifiable_type_user' => 'Wasateam\Laravelapistone\Models\Admin',
  ],
  'service_store'      => [
    'appointment' => [
      'notify' => [
        'today_appointments'    => [
          'default' => '16:00:00',
        ],
        'tomorrow_appointments' => [
          'default' => '07:00:00',
        ],
      ],
    ],
  ],
  'appointment'        => [
    'export' => true,
  ],
  'service_plan'       => false,
  'pin_card'           => false,
  'calendar_highlight' => false,
  'shop'               => [
    'uuid'            => true,
    'pre_order'       => true,
    'current'         => true,
    'order_export'    => true,
    //購物須知
    'notice'          => true,
    //促銷活動
    'shop_campaign'   => [
      'items' => [ //活動類型分別設定
        'bonus_point_feedback' => [
          'feedback_after_invoice_days' => 3, //開立發票後多久發紅利
          'date_no_repeat'              => true, //日期是否重複
        ],
      ],
    ],
    'freight_default' => 100,
    'order'           => [
      'per_page' => 100,
      'export'   => [
        'created_at' => [
          'format' => 'Y/m/d',
        ],
        'ship_date'  => [
          'format' => 'Y/m/d',
        ],
      ],
    ],
    'product'         => [
      'per_page' => 100,
    ],
    'order_type'      => [
      'next-day'  => [
        'title'                  => '超市',
        'freight_default'        => 100,
        'has_shop_free_shipping' => true,
      ],
      'pre-order' => [
        'title'            => '預購',
        'freight_separate' => true,
      ],
    ],
    'pay_expire'      => [
      'time_limit' => 600,
    ],
  ],
  'invoice'            => [
    'service'    => 'ecpay',
    'mode'       => env('INVOICE_MODE', 'dev'),
    'delay'      => 3,
    'notify_url' => env('APP_URL') . env('ECPAY_INVOICE_NOTIFY_URL', "/api/callback/invoice/notify"),
    'ecpay'      => [
      'merchant_id' => env('INVOICE_ECPAY_MERCHANT_ID', "2000132"),
      'hash_key'    => env('INVOICE_ECPAY_HASH_KEY', "5294y06JbISpM5x9"),
      'hash_iv'     => env('INVOICE_ECPAY_HASH_IV', "v77hoKGq4kWxNNIS"),
    ],
  ],
  // multiple layers
  'featured_class'     => true,
  #
  'app'                => false,
  'privacy_terms'      => true,
  'ez_about_us'        => true,
  'country_code'       => true,
  'finance'            => [
    'payment_request' => true,
  ],
  'acumatica'          => [
    'app_mode'      => true,
    'token_url'     => env('ACUMATICA_TOKEN_URL'),
    'api_url'       => env('ACUMATICA_API_URL'),
    'client_id'     => env('ACUMATICA_CLIENT_ID'),
    'client_secret' => env('ACUMATICA_CLIENT_SECRET'),
    'username'      => env('ACUMATICA_USERNAME'),
    'password'      => env('ACUMATICA_PASSWORD'),
  ],
  //banner
  'news_banner'        => true,
  'news'               => true,
  //頁面設定
  'page_setting'       => true,
  //頁面彈跳視窗
  'page_cover'         => true,
  'xc_work_type'       => true,
  'xc_task'            => true,
  'xc_milestone'       => true,
  'xc_project'         => true,
  //點數系統
  'bonus_point'        => [
    'shop_freight_deduct' => true,
  ],
  'lottery'            => [
    'recaptcha'   => [
      'secret' => '6Lf2LWQfAAAAANb1hN-HihBNGo5G7N7CD8uBcRMc',
    ],
    'participant' => [
      'create_buffer' => [
        'service' => 'pubsub',
        'encode'  => true,
      ],
    ],
  ],
  'download_info'      => [
    'clone_types' => [
      'financial-statement' => [
        'title' => '財務報表',
      ],
      'monthly-revenue'     => [
        'title' => '月營收資訊',
      ],
    ],
  ],
  'google'             => [
    'credentials_path' => env('GOOGLE_APPLICATION_CREDENTIALS'),
  ],
];
