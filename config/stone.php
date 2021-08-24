<?php

return [
  'mode'              => env('STONE_MODE', 'cms'), // cms, webapi
  'uuid' => env('UUID', false),
  'app_mode'          => env('APP_MODE', false),
  'user_device_token' => env('USER_DEVICE_TOKEN', false),
  'storage'           => [
    'signed_url' => env('STONE_STORAGE_SIGNED_URL', false),
    'service'    => env('STONE_STORAGE_SERVICE', 'gcs'), // gcs, local, s3
    'gcs' => [
      'bucket' => env('GOOGLE_CLOUD_STORAGE_BUCKET', null),
    ],
    'acl'        => env('STONE_STORAGE_ACL', true),
  ],
  'mail'              => [
    'service'    => env('gmail'), // gmail, surenotify
    'api_key' => env('MAIL_API_KEY'),
    'api_domain' => env('MAIL_API_DOMAIN'),
  ],
  'log'               => [
    'is_active' => env('STONE_LOG_ACTIVE', true),
    'model'     => '\Wasateam\Laravelapistone\Models\CmsLog',
    // 'model'     => '\Wasateam\Laravelapistone\Models\WebLog',
  ],
  'app_url'           => env('APP_URL'),
  'web_url'           => env('WEB_URL'),
  // Auth
  'auth'              => [
    'model_name'       => 'user',
    'model'            => '\Wasateam\Laravelapistone\Models\User',
    'resource'         => '\Wasateam\Laravelapistone\Resources\User',
    'auth_scope'       => 'user',
    'default_scopes'   => [
      'boss',
      'user',
    ],
    //   'model_name'     => 'admin',
    //   'model'          => '\Wasateam\Laravelapistone\Models\Admin',
    //   'resource'       => '\Wasateam\Laravelapistone\Resources\Admin',
    //   'auth_scope'     => 'admin',
    //   'default_scopes' => [
    //     'admin',
    //   ],
    'active_check'     => true,
    'has_role'         => true,
    'has_system_class' => true,
  ],
  'notification'      => [
    'notifiable_type_user' => 'Wasateam\Laravelapistone\Models\User',
  ],
  'admin_group'       => env('ADMIN_GROUP', false),
  'contact_request'   => [
    'notify_mail' => env('CONTACT_REQUEST_NOTIFY_MAIL'),
  ],
  // Member
  // 'user'    => [
  //   'model_name' => 'user',
  //   'model'      => '\Wasateam\Laravelapistone\Models\User',
  //   'resource'   => '\Wasateam\Laravelapistone\Resources\User',
  // ],
];
