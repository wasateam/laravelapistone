<?php

return [
  'mode'         => env('STONE_MODE', 'cms'), // cms, webapi
  'user_device_token' => env('USER_DEVICE_TOKEN', false),
  'storage'      => [
    'signed_url' => env('STONE_STORAGE_SIGNED_URL', false),
    'service'    => env('STONE_STORAGE_SERVICE', 'gcs'), // gcs, local, s3
    'gcs' => [
      'bucket' => env('GOOGLE_CLOUD_STORAGE_BUCKET', null),
    ],
    'acl'        => env('STONE_STORAGE_ACL', true),
  ],
  'mail'         => [
    'service'    => env('gmail'), // gmail, surenotify
    'api_key' => env('MAIL_API_KEY'),
    'api_domain' => env('MAIL_API_DOMAIN'),
  ],
  'log'          => [
    'is_active' => env('STONE_LOG_ACTIVE', true),
    'model'     => '\Wasateam\Laravelapistone\Models\CmsLog',
    // 'model'     => '\Wasateam\Laravelapistone\Models\WebLog',
  ],
  'app_url'      => env('APP_URL'),
  'web_url'      => env('WEB_URL'),
  // User
  'auth'         => [
    'model_name'       => 'user',
    'model'            => '\Wasateam\Laravelapistone\Models\User',
    'resource'         => '\Wasateam\Laravelapistone\Resources\User',
    'auth_scope'       => 'user',
    'default_scopes'   => [
      'user',
    ],
    'active_check'     => false,
    'has_role'         => false,
    'has_system_class' => true,
  ],
  'notification' => [
    'notifiable_type_user' => 'Wasateam\Laravelapistone\Models\User',
  ],
  // 'user'    => [
  //   'model_name' => 'user',
  //   'model'      => '\Wasateam\Laravelapistone\Models\User',
  //   'resource'   => '\Wasateam\Laravelapistone\Resources\User',
  // ],
  // Admin
  // 'auth'    => [
  //   'model_name'     => 'admin',
  //   'model'          => '\Wasateam\Laravelapistone\Models\Admin',
  //   'resource'       => '\Wasateam\Laravelapistone\Resources\Admin',
  //   'auth_scope'     => 'admin',
  //   'default_scopes' => [
  //     'admin',
  //   ],
  //   'active_check'   => false,
  //   'has_role'   => false,
  // ],
];
