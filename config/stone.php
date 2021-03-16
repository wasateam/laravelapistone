<?php

return [
  'signed_url' => true,
  'storage'    => [
    'service' => 'gcs', // gcs, app, s3
    'gcs'     => [
      'bucket' => env('GOOGLE_CLOUD_STORAGE_BUCKET', null),
    ],
  ],
  // User
  'auth'       => [
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
  // 'auth'            => [
  //   'model_name'     => 'user',
  //   'model'          => '\Wasateam\Laravelapistone\Models\User',
  //   'resource'       => '\Wasateam\Laravelapistone\Resources\User',
  //   'auth_scope'     => 'user',
  //   'default_scopes' => [
  //     'user',
  //   ],
  //   'active_check'   => false,
  // ],
];
