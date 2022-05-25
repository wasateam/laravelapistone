<?php

\WsRoute::get_resource_scope_routes(
  \Wasateam\Laravelapistone\Modules\AppDeveloper\App\Controllers\AppDeveloperController::class,
  'app_developer',
  ['read', 'edit']
);
