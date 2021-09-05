<?php

namespace Wasateam\Laravelapistone\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Wasateam\Laravelapistone\Helpers\ModelHelper;

/**
 * @group AppointmentAvailable
 *
 * @authenticated
 *
 * APIs for appointment_available
 */
class AppointmentAvailableController extends Controller
{
  public $model        = 'Wasateam\Laravelapistone\Models\AppointmentAvailable';
  public $name         = 'appointment_available';
  public $resource     = 'Wasateam\Laravelapistone\Resources\AppointmentAvailable';
  public $input_fields = [
    'start_time',
    'end_time',
  ];
  public $belongs_to = [
    'service_store',
  ];
  public $filter_belongs_to = [
    'service_store',
  ];
  public $order_fields = [
    'start_time',
    'end_time',
    'updated_at',
    'created_at',
  ];

  /**
   * Index
   *
   * @queryParam  search string Search on locales.name match No-example
   * @urlParam area int Example:1
   *
   */
  public function index(Request $request, $id = null)
  {
    return ModelHelper::ws_IndexHandler($this, $request, $id, true);
  }

  /**
   * Store
   *
   * @bodyParam  sequence string Example:1
   * @bodyParam  start_time string Example: 1000
   * @bodyParam  end_time string Example: 1200
   * @bodyParam  service_store string Example: 1
   */
  public function store(Request $request, $id = null)
  {
    return ModelHelper::ws_StoreHandler($this, $request, $id);
  }

  /**
   * Show
   *
   * @urlParam  appointment_available required The ID of appointment_available. Example: 1
   */
  public function show(Request $request, $id = null)
  {
    return ModelHelper::ws_ShowHandler($this, $request, $id);
  }

  /**
   * Update
   *
   * @urlParam  appointment_available required The ID of appointment_available. Example: 1
   * @bodyParam  start_time string Example: 1000
   * @bodyParam  end_time string Example: 1200
   * @bodyParam  service_store string Example: 1
   */
  public function update(Request $request, $id)
  {
    return ModelHelper::ws_UpdateHandler($this, $request, $id);
  }

  /**
   * Delete
   *
   * @urlParam  appointment_available required The ID of appointment_available. Example: 2
   */
  public function destroy($id)
  {
    return ModelHelper::ws_DestroyHandler($this, $id);
  }
}
