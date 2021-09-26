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
    'model_name'       => 'admin',
    'model'            => '\Wasateam\Laravelapistone\Models\Admin',
    'resource'         => '\Wasateam\Laravelapistone\Resources\Admin',
    'auth_scope'       => 'admin',
    'default_scopes'   => [
      'boss',
      'admin',
    ],
    'active_check'     => false,
  ],
  // Models
  'admin_blur'         => false,
  'admin_role'         => false,
  'admin_group'        => false,
  'cms_log'            => false,
  // 'cms_log'            => [
  //   'is_active' => true,
  //   'model'     => '\Wasateam\Laravelapistone\Models\CmsLog',
  // ],
  'user'               => false,
  'locale'             => false,
  'notification'       => false,
  'contact_request'    => false,
  'app'                => false,
  'appointment'        => false,
  'service_plan'       => false,
  'pin_card'           => false,
  'calendar_highlight' => false,
  'post_encode'        => false,
];
