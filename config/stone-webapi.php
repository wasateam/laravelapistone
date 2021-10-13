<?php

return [
  'mode'               => 'webapi',
  'storage'            => [
    'service' => 'gcs', # gcs, s3, local
    'gcs' => [
      'bucket' => env('GOOGLE_CLOUD_STORAGE_BUCKET', null),
    ],
    'acl'     => true,
  ],
  'mail'               => [
    'service'    => env('gmail'), // gmail, surenotify
    'api_key' => env('MAIL_API_KEY'),
    'api_domain' => env('MAIL_API_DOMAIN'),
  ],
  'app_url'            => env('APP_URL'),
  'web_url'            => env('WEB_URL'),
  // Auth
  'auth'               => [
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
  'log'                => [
    'is_active' => true,
    'model'     => '\Wasateam\Laravelapistone\Models\WebLog',
  ],
  'post_encode'        => false,
  'tulpa'              => true,
  'socialite'          => [
    'facebook' => true,
    'google'   => true,
    'line'     => true,
  ],
  'user_device_token'  => true,
  'ws_blog'            => true,
  'tag'                => true,
  'area'               => true,
  'system_class'       => true,
  'contact_request'    => false,
  'locale'             => false,
  'service_store'      => false,
  'notification'       => true,
  'appointment'        => false,
  'service_plan'       => false,
  'pin_card'           => false,
  'calendar_highlight' => false,
  'file_upload'        => 'laravel_signed',
];
