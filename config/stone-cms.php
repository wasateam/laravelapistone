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
  'log'                => [
    'is_active' => true,
    'model'     => '\Wasateam\Laravelapistone\Models\CmsLog',
  ],
  'post_encode'        => false,
  'pocket'             => true,
  'tulpa'              => true,
  'user'               => [
    'export'              => true,
    'is_bad'              => true,
    'bonus_points'        => true,
    'reset_password_mail' => true,
    'carriers'            => true,
    //如果地址無類別，改成true
    'address'             => [
      'delivery' => [
        'limit' => 3,
      ],
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
  'appointment'        => false,
  'service_plan'       => false,
  'pin_card'           => false,
  'calendar_highlight' => false,
  'shop'               => [
    'uuid'            => true,
    'pre_order'       => true,
    'current'         => true,
    'order_export'    => true,
    'freight_default' => 100,
    //購物須知
    'notice'          => true,
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
    'mode'          => env('ACUMATICA_MODE', 'uat'),
    'client_id'     => env('ACUMATICA_CLIENT_ID'),
    'client_secret' => env('ACUMATICA_CLIENT_SECRET'),
    'username'      => env('ACUMATICA_USERNAME'),
    'password'      => env('ACUMATICA_PASSWORD'),
  ],
  'news_banner'        => true,
  'news'               => true,
];
