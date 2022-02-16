<?php

namespace Wasateam\Laravelapistone\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Wasateam\Laravelapistone\Helpers\ModelHelper;
use Auth;

class UserServicePlanItemRecordController extends Controller
{
    public function get_acumatica_records(Request $request, $id = null)
  {
    if(config('stone.user.service_history')){
      return config('stone.user.service_history')::getUserOrderHistory(Auth::user());
    }
    return ModelHelper::ws_IndexHandler($this, $request, $id);
  }
}
