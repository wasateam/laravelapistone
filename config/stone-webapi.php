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
    'service'    => env('gmail'), // gmail, surenotify
    'api_key'    => env('MAIL_API_KEY'),
    'api_domain' => env('MAIL_API_DOMAIN'),
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
  'contact_request'     => false,
  'locale'              => false,
  'service_store'       => false,
  'notification'        => true,
  'appointment'         => false,
  'service_plan'        => false,
  'pin_card'            => false,
  'calendar_highlight'  => false,
  'shop'                => [
    'uuid'      => true,
    'pre_order' => true,
    'current'   => true,
  ],
  'file_upload'         => 'laravel_signed',
  'privacy_terms'       => true,
  'thrid_party_payment' => [
    'service' => 'ecpay_inpay',
    'mode'    => env('ECPAY_MODE', 'dev'),
    'ecpay_inpay'   => [
      'insite_order_return_url' => env('APP_URL') . env('ECPAY_INSITE_ORDER_RETURN_URL', "/api/callback/ecpay/insite/order"),
      'cardinfo'                => [
        '3d_order_return_url' => env('APP_URL') . env('ECPAY_CARDINFO_3DORDER_RETURN_URL', "/api/callback/ecpay/cardinfo/3dorder"),
        'period_return_url'   => env('APP_URL') . env('ECPAY_CARDINFO_PERIOD_RETURN_URL', "/api/callback/ecpay/cardinfo/period"),
      ],
      'merchant_id'             => env('ECPAY_MERCHANT_ID', "3002607"),
      'uniform_no'              => env('ECPAY_UNIFORM_NO', "00000000"),
      'cms_account'             => env('ECPAY_CMS_ACCOUNT', "stagetest3"),
      'cms_password'            => env('ECPAY_CMS_PASSWORD', "test1234"),
      'hash_key'                => env('ECPAY_HASH_KEY', "pwFHCqoQZGmho4w6"),
      'hash_iv'                 => env('ECPAY_HASH_IV', "EkRm7iFT261dpevs"),
      'cms_link'                => env('ECPAY_CMS_LINK', "https://vendor-stage.ecpay.com.tw"),
      'card_no'                 => env('ECPAY_CARD_NO', "4311-9522-2222-2222"),
      'card_safe_no'            => env('ECPAY_CARD_SAFE_NO', "222"),
    ],
  ],
];
