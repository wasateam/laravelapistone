<?php

return [
  'mode'               => 'cms',
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
    'test_mail'  => env('MAIL_API_DOMAIN', 'wasalearn@gmail.com'),
  ],
  'app_url'            => env('APP_URL'),
  'web_url'            => env('WEB_URL'),
  'web_api_url'        => env('WEB_API_URL'),
  # Auth
  'auth'               => [
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
    'export'              => true,
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
  'locale'             => false,
  'notification'       => [
    'notifiable_type_user' => 'Wasateam\Laravelapistone\Models\Admin',
  ],
  'service_store'      => false,
  'appointment'        => true,
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
    'order_type'      => [
      'next_day'  => [
        'freight_default'        => 100,
        'has_shop_free_shipping' => true,
      ],
      'pre_order' => [
        'freight_separate' => true,
      ],
    ],
  ],
  // multiple layers
  'featured_class'     => true,
  #
  'app'                => false,
  'privacy_terms'      => true,
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
  //banner 群組
  'news_banner_group'  => true,
  'news'               => true,
  //頁面設定
  'page_setting'       => true,
  //頁面彈跳視窗
  'page_cover'         => true,
  'xc_work_type'       => true,
  'xc_task'            => true,
  'xc_milestone'       => true,
  'xc_project'         => true,
  // LINE Pay
  'linepay'            => [
    'channel_id' => env('LINEPAY_CHANNEL_ID'),
    'secret_key' => env('LINEPAY_CHENNEL_SECRET_KEY'),
    'url'        => env('LINEPAY_API_URL'),
  ],
];
