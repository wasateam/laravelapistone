<?php

\WsRoute::get_resource_scope_routes(
  \Wasateam\Laravelapistone\Modules\Otp\App\Controllers\OtpController::class,
  'otp',
  ['read']
);
