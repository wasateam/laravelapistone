<?php

return [
  'mode'               => 'cms',
  'storage'            => [
    'service' => 'gcs', // gcs, s3, local
    'gcs' => [
      'bucket' => env('GOOGLE_CLOUD_STORAGE_BUCKET', null),
    ],
    'acl'     => true,
  ],
  'mail'               => [
    'service'    => 'gmail', // gmail, surenotify
    'api_key' => env('MAIL_API_KEY'),
    'api_domain' => env('MAIL_API_DOMAIN'),
  ],
  'app_url'            => env('APP_URL'),
  'web_url'            => env('WEB_URL'),
  // Auth
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
  // Models
  'admin_blur'         => true,
  'admin_role'         => true,
  'admin_group'        => true,
  'admin_system_class' => true,
  'log'                => [
    'is_active' => true,
    'model'     => '\Wasateam\Laravelapistone\Models\CmsLog',
  ],
  'pocket'             => true,
  'tulpa'              => true,
  'user'               => true,
  'user_device_token'  => true,
  'socialite'          => true,
  'ws_blog'            => true,
  'tag'                => true,
  'area'               => true,
  'system_class'       => true,
  'contact_request'    => false,
  'locale'             => false,
  'notification'       => [
    'notifiable_type_user' => 'Wasateam\Laravelapistone\Models\Admin',
  ],
  'appointment'        => false,
  'service_plan'       => false,
  'pin_card'           => false,
  'calendar_highlight' => false,
  'post_encode'        => false,
  'app'                => false,
];
