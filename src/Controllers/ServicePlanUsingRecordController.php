<?php

namespace Wasateam\Laravelapistone\Controllers;

use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Http\Request;
use Wasateam\Laravelapistone\Helpers\AcumaticaHelper;
use Wasateam\Laravelapistone\Helpers\ServicePlanHelper;

/**
 * @group ServicePlanUsingRecord
 *
 * @authenticated
 *
 * APIs for acumatica_app
 */
class ServicePlanUsingRecordController extends Controller
{

  /**
   * Index
   *
   */
  public function index(Request $request, $id = null)
  {
    if (stone('stone.service_plan.using_record.from') == 'acumatica') {
      $acumatica_res                 = AcumaticaHelper::getHighcareServiceHistory(Auth::user());
      $HighcareServiceHistoryDetails = $acumatica_res['HighcareServiceHistoryDetails'];
      $models                        = ServicePlanHelper::getServicePlanUsingRecordsFromAcumatica($HighcareServiceHistoryDetails);
      return response()->json([
        'data' => $models,
      ], 200);
    }
  }
}
