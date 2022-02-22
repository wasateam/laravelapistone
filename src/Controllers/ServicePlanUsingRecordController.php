<?php

namespace Wasateam\Laravelapistone\Controllers;

use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Http\Request;
use Wasateam\Laravelapistone\Helpers\AcumaticaHelper;
use Wasateam\Laravelapistone\Helpers\ServicePlanHelper;
use Wasateam\Laravelapistone\Models\UserServicePlan;

/**
 * @group ServicePlanUsingRecord
 *
 * @authenticated
 * user_service_plan 使用者綁定方案
 *
 */
class ServicePlanUsingRecordController extends Controller
{

  /**
   * Index
   *
   * @queryParam user_service_plan id Example:1
   *
   */
  public function index(Request $request, $id = null)
  {
    if (config('stone.mode') == 'cms') {
    } else if (config('stone.mode') == 'webapi') {
      if (!$request->has('user_service_plan')) {
        throw new \Wasateam\Laravelapistone\Exceptions\FieldRequiredException('user_service_plan');
      }
      if (config('stone.service_plan.using_record.from') == 'acumatica') {
        $user_service_plan = UserServicePlan::where('id', $request->user_service_plan)
          ->where('user_id', Auth::user()->id)
          ->first();
        if (!$user_service_plan) {
          throw new \Wasateam\Laravelapistone\Exceptions\FindNoDataException('user_service_plan');
        }
        $pin                           = $user_service_plan->pin_card->pin;
        $user                          = Auth::user();
        $acumatica_res                 = AcumaticaHelper::getHighcareServiceHistory(Auth::user());
        $HighcareServiceHistoryDetails = $acumatica_res['HighcareServiceHistoryDetails'];
        $models                        = ServicePlanHelper::getServicePlanUsingRecordsFromAcumatica($HighcareServiceHistoryDetails, $user, $pin);
        return response()->json([
          'data' => $models,
        ], 200);
      }
    }
  }
}
