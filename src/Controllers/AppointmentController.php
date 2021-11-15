<?php

namespace Wasateam\Laravelapistone\Controllers;

use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Http\Request;
use Wasateam\Laravelapistone\Helpers\ModelHelper;
use Wasateam\Laravelapistone\Models\Appointment;

/**
 * @group Appointment
 *
 * @authenticated
 *
 * APIs for appointment
 */
class AppointmentController extends Controller
{
  public $model        = 'Wasateam\Laravelapistone\Models\Appointment';
  public $name         = 'appointment';
  public $resource     = 'Wasateam\Laravelapistone\Resources\Appointment';
  public $input_fields = [
    'start_time',
    'end_time',
    'date',
    'tel',
    'email',
    'type',
    'remark',
  ];
  public $belongs_to = [
    'user',
    'service_store',
  ];
  public $order_fields = [
    'updated_at',
    'created_at',
    'date',
  ];

  /**
   * Index
   * @queryParam search string No-example
   *
   */
  public function index(Request $request, $id = null)
  {
    if (config('stone.mode') == 'cms') {
      return ModelHelper::ws_IndexHandler($this, $request, $id);
    } else if (config('stone.mode') == 'webapi') {
      return ModelHelper::ws_IndexHandler($this, $request, $id, false, function ($snap) {
        $user = Auth::user();
        $snap = $snap->where('user_id', $user->id);
        return $snap;
      });
    }
  }

  /**
   * Store
   *
   * @bodyParam start_time string Example: 1000
   * @bodyParam end_time string Example: 1200
   * @bodyParam date date Example: 2021-03-13
   * @bodyParam user string Example :1
   * @bodyParam service_store Example :1
   * @bodyParam tel String Example: 02-2222-2222
   * @bodyParam email String Example: aa@aa.aa
   * @bodyParam type String No-example
   * @bodyParam remark String No-example
   */
  public function store(Request $request, $id = null)
  {
    if (config('stone.mode') == 'cms') {
      return ModelHelper::ws_StoreHandler($this, $request, $id);
    } else if (config('stone.mode') == 'webapi') {
      return ModelHelper::ws_StoreHandler($this, $request, $id, function ($model) {
        $user           = Auth::user();
        $model->user_id = $user->id;
        $model->save();
      });
    }
  }

  /**
   * Show
   *
   * @urlParam  appointment required The ID of appointment. Example: 1
   */
  public function show(Request $request, $id = null)
  {

    if (config('stone.mode') == 'cms') {
      return ModelHelper::ws_ShowHandler($this, $request, $id);
    } else if (config('stone.mode') == 'webapi') {
      return ModelHelper::ws_ShowHandler($this, $request, $id, function ($snap) {
        $user = Auth::user();
        $snap = $snap->where('user_id', $user->id);
        return $snap;
      });
    }
  }

  /**
   * Update
   *
   * @urlParam  appointment required The ID of appointment. Example: 1
   * @bodyParam start_time string Example: 1000
   * @bodyParam end_time string Example: 1200
   * @bodyParam date date Example: 2021-03-13
   * @bodyParam user string Example :1
   * @bodyParam service_store Example :1
   * @bodyParam tel String Example: 02-2222-2222
   * @bodyParam email String Example: aa@aa.aa
   * @bodyParam type String No-example
   * @bodyParam remark String No-example
   */
  public function update(Request $request, $id)
  {
    if (config('stone.mode') == 'cms') {
      return ModelHelper::ws_UpdateHandler($this, $request, $id);
    } else if (config('stone.mode') == 'webapi') {
      return ModelHelper::ws_UpdateHandler($this, $request, $id, [], null, function ($model) {
        $user = Auth::user();
        if ($model->user_id == $user->id) {
          return true;
        } else {
          return false;
        }
      });
    }
  }

  /**
   * Delete
   *
   * @urlParam  appointment required The ID of appointment. Example: 2
   */
  public function destroy($id)
  {
    if (config('stone.mode') == 'cms') {
      return ModelHelper::ws_DestroyHandler($this, $id);
    } else if (config('stone.mode') == 'webapi') {
      return ModelHelper::ws_DestroyHandler($this, $id, null, null, function ($model) {
        $user = Auth::user();
        if ($model->user_id == $user->id) {
          return true;
        } else {
          return false;
        }
      });
    }
  }
}
