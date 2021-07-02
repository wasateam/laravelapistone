<?php

return [
  'mode'    => 'cms', // cms, webapi
  'storage' => [
    'signed_url' => false,
    'service'    => 'gcs', // gcs, app, s3
    'gcs'        => [
      'bucket' => env('GOOGLE_CLOUD_STORAGE_BUCKET', null),
    ],
  ],
  'mail'    => [
    'service'    => 'surenotify', // gmail, surenotify
    'api_key'    => env('MAIL_API_KEY'),
    'api_domain' => env('MAIL_API_DOMAIN'),
  ],
  'log'     => [
    'is_active' => true,
    'model'     => '\Wasateam\Laravelapistone\Models\CmsLog',
    // 'model'     => '\Wasateam\Laravelapistone\Models\WebLog',
  ],
  'app_url' => env('APP_URL'),
  'web_url' => env('WEB_URL'),
  // User
  'auth'    => [
    'model_name'     => 'user',
    'model'          => '\Wasateam\Laravelapistone\Models\User',
    'resource'       => '\Wasateam\Laravelapistone\Resources\User',
    'auth_scope'     => 'user',
    'default_scopes' => [
      'user',
    ],
    'active_check'   => false,
    'has_role'   => false,
    // 'has_system_class' => true,
  ],
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
