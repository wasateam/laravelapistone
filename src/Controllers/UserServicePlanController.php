<?php

namespace Wasateam\Laravelapistone\Controllers;

use App\Http\Controllers\Controller;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Wasateam\Laravelapistone\Helpers\ModelHelper;
use Wasateam\Laravelapistone\Resources\UserServicePlan;

/**
 * @group UserServicePlan 使用者綁定方案
 * @authenticated
 *
 * service_plan 服務方案
 * user 使用者
 * pin_card PinCard
 * expired_at PinCard
 *
 */
class UserServicePlanController extends Controller
{
  public $model        = 'Wasateam\Laravelapistone\Models\UserServicePlan';
  public $name         = 'user_service_plan';
  public $resource     = 'Wasateam\Laravelapistone\Resources\UserServicePlan';
  public $input_fields = [
    'expired_at',
  ];
  public $search_fields = [
  ];
  public $belongs_to = [
    'service_plan',
    'pin_card',
  ];
  public $filter_belongs_to = [
    'service_plan',
  ];
  public $order_fields = [
    'updated_at',
    'created_at',
  ];
  public $search_relationship_fields = [
    'user'     => [
      'name',
      'email',
      'tel',
    ],
    'pin_card' => [
      'pin',
    ],
  ];

  public function __construct()
  {
    if (config('stone.mode') == 'cms') {
      $this->belongs_to[]        = 'user';
      $this->filter_belongs_to[] = 'user';
    }
    if (config('stone.pin_card')) {
      $this->belongs_to[]        = 'pin_card';
      $this->filter_belongs_to[] = 'pin_card';
    }
  }

  /**
   * Index
   * 
   * @queryParam search string No-example
   *
   */
  public function index(Request $request, $id = null)
  {
    if (config('stone.mode') == 'cms') {
      return ModelHelper::ws_IndexHandler($this, $request, $id);
    } else if (config('stone.mode') == 'webapi') {
      return ModelHelper::ws_IndexHandler($this, $request, $id, false, function ($snap) {
        $snap = $snap->where('user_id', Auth::user()->id);
        return $snap;
      });
    }
  }

  /**
   * Store
   *
   * @bodyParam user string Example: 1
   * @bodyParam service_plan string Example: 1
   * @bodyParam pin_card string Example: 1
   */
  public function store(Request $request, $id = null)
  {
    return ModelHelper::ws_StoreHandler($this, $request, $id);
  }

  /**
   * Show
   *
   * @urlParam  user_service_plan required The ID of user_service_plan. Example: 1
   */
  public function show(Request $request, $id = null)
  {

    if (config('stone.mode') == 'cms') {
      return ModelHelper::ws_ShowHandler($this, $request, $id);
    } else if (config('stone.mode') == 'webapi') {
      return ModelHelper::ws_ShowHandler($this, $request, $id, function ($snap) {
        $snap = $snap->where('user_id', Auth::user()->id);
        return $snap;
      });
    }
  }

  /**
   * Update
   *
   * @urlParam  user_service_plan required The ID of user_service_plan. Example: 1
   * @bodyParam user string Example: 1
   * @bodyParam service_plan string Example: 1
   * @bodyParam pin_card string Example: 1
   */
  public function update(Request $request, $id)
  {
    return ModelHelper::ws_UpdateHandler($this, $request, $id);
  }

  /**
   * Delete
   *
   * @urlParam  user_service_plan required The ID of user_service_plan. Example: 2
   */
  public function destroy($id)
  {
    return ModelHelper::ws_DestroyHandler($this, $id);
  }

  /**
   * Get Current
   *
   */
  public function get_current()
  {
    $user              = Auth::user();
    $user_service_plan = $this->model::where('user_id', $user->id)
      ->where('expired_at', '>=', Carbon::now())
      ->orWhereNull('expired_at')
      ->orderBy('created_at', 'desc')
      ->first();
    if (!$user_service_plan) {
      throw new \Wasateam\Laravelapistone\Exceptions\FindNoDataException('user_service_plan');
    }
    return response()->json(new $this->resource($user_service_plan), 200);
  }
}
