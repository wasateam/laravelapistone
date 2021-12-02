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
    'service'    => env('MAIL_MAILER'), // gmail, surenotify
    'api_key'    => env('MAIL_API_KEY'),
    'api_domain' => env('MAIL_API_DOMAIN'),
    'test_mail'  => env('MAIL_API_DOMAIN', 'wasalearn@gmail.com'),
  ],
  'app_url'             => env('APP_URL'),
  'web_url'             => env('WEB_URL'),
  // Auth
  'auth'                => [
    'model_name'     => 'user',
    'model'          => '\Wasateam\Laravelapistone\Models\User',
    'resource'       => '\Wasateam\Laravelapistone\Resources\User',
    'auth_scope'     => 'user',
    'default_scopes' => [
      'user',
    ],
    'active_check'   => true,
    'verify'         => [
      'email' => true,
    ],
    // 'signup_complete_action' => '\App\Helpers\TestHelper',
  ],
  # Modules
  'log'                 => [
    'is_active' => true,
    'model'     => '\Wasateam\Laravelapistone\Models\WebLog',
  ],
  'post_encode'         => false,
  'tulpa'               => true,
  'socialite'           => [
    'facebook' => true,
    'google'   => true,
    'line'     => true,
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
  'appointment'         => false,
  'service_plan'        => false,
  'pin_card'            => false,
  'calendar_highlight'  => false,
  'user'                => [
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
  'shop'                => [
    'uuid'              => true,
    'pre_order'         => true,
    'current'           => true,
    'custom_shop_order' => true,
  ],
  'file_upload'         => 'laravel_signed',
  'privacy_terms'       => true,
  'thrid_party_payment' => [
    'service'     => 'ecpay_inpay',
    'mode'        => env('ECPAY_MODE', 'dev'),
    'ecpay_inpay' => [
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
];
