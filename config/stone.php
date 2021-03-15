<?php

return [
  'signed_url'      => true,
  'storage_service' => 'gcs',
  // User
  'auth'            => [
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
