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
  'log'     => [
    'is_active' => true,
    'model'     => '\Wasateam\Laravelapistone\Models\CmsLog',
    // 'model'     => '\Wasateam\Laravelapistone\Models\WebLog',
  ],
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
  // ],
];
