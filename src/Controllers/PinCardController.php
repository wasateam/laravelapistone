<?php

namespace Wasateam\Laravelapistone\Controllers;

use App\Http\Controllers\Controller;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use Wasateam\Laravelapistone\Helpers\LogHelper;
use Wasateam\Laravelapistone\Helpers\ModelHelper;
use Wasateam\Laravelapistone\Helpers\ServicePlanHelper;
use Wasateam\Laravelapistone\Helpers\StrHelper;
use Wasateam\Laravelapistone\Models\PinCard;
use Wasateam\Laravelapistone\Models\ServicePlanItem;
use Wasateam\Laravelapistone\Models\UserServicePlan;
use Wasateam\Laravelapistone\Models\UserServicePlanItem;

/**
 * @group PinCard
 *
 * @authenticated
 *
 * APIs for pin_card
 */
class PinCardController extends Controller
{
  public $model        = 'Wasateam\Laravelapistone\Models\PinCard';
  public $name         = 'pin_card';
  public $resource     = 'Wasateam\Laravelapistone\Resources\PinCard';
  public $input_fields = [
    'pin',
    'status',
  ];
  public $belongs_to = [
    'service_plan',
    'user',
  ];
  public $search_fields = [
    'pin',
  ];
  public $order_fields = [
    'updated_at',
    'created_at',
  ];
  public $search_relationship_fields = [
    'user' => [
      'name',
    ],
  ];
  public $user_create_field = 'created_admin_id';

  /**
   * Index
   * @queryParam search string No-example
   *
   */
  public function index(Request $request, $id = null)
  {
    return ModelHelper::ws_IndexHandler($this, $request, $id);
  }

  /**
   * Store
   *
   * @bodyParam pin string Example: FK1FA0I0Zg90
   * @bodyParam service_plan string Example: 1
   */
  public function store(Request $request, $id = null)
  {
    return ModelHelper::ws_StoreHandler($this, $request, $id);
  }

  /**
   * Show
   *
   * @urlParam  pin_card required The ID of pin_card. Example: 1
   */
  public function show(Request $request, $id = null)
  {
    return ModelHelper::ws_ShowHandler($this, $request, $id);
  }

  /**
   * Update
   *
   * @urlParam  pin_card required The ID of pin_card. Example: 1
   * @bodyParam pin string Example: FK1FA0I0Zg90
   * @bodyParam service_plan string Example: 1
   */
  public function update(Request $request, $id)
  {
    return ModelHelper::ws_UpdateHandler($this, $request, $id);
  }

  /**
   * Delete
   *
   * @urlParam  pin_card required The ID of pin_card. Example: 2
   */
  public function destroy($id)
  {
    return ModelHelper::ws_DestroyHandler($this, $id);
  }

  /**
   * Generate
   *
   * @bodyParam service_plan string Example: 1
   */
  public function generate(Request $request)
  {
    $request->validate([
      'count' => 'required|numeric',
    ]);
    $user         = Auth::user();
    $count        = $request->count;
    $service_plan = $request->service_plan;

    for ($i = 0; $i < $count; $i++) {
      $try_count = 0;
      while ($try_count < 3) {
        try {
          $model                   = new $this->model;
          $model->pin              = StrHelper::generateRandomString(12, '23456789ABCDEFGHJKLMNPQRSTUVWXYZ');
          $model->created_admin_id = $user->id;
          $model->service_plan_id  = $service_plan;
          $model->save();
          break;
        } catch (\Throwable $th) {
        }
        $try_count++;
      }
    }
    return response()->json([
      'message' => 'ok',
    ]);
  }

  /**
   * Register 註冊
   * 兌換服務卡
   *
   */
  public function register(Request $request)
  {
    $request->validate([
      'pin' => 'required|string',
    ]);
    $user  = Auth::user();
    $model = $this->model::where('pin', $request->pin)->where('status', 0)->first();
    if (!$model) {
      throw new \Wasateam\Laravelapistone\Exceptions\FindNoDataException('pin_card');
    }
    if (config('stone.pin_card.register_before_action')) {
      config('stone.pin_card.register_before_action')::pin_card_register_before_action($model, $user);
    }
    $model->status  = 1;
    $model->user_id = $user->id;
    $model->save();
    $user_service_plan                  = new UserServicePlan;
    $user_service_plan->user_id         = $user->id;
    $user_service_plan->service_plan_id = $model->service_plan_id;
    $user_service_plan->pin_card_id     = $model->id;
    $user_service_plan->save();
    ServicePlanHelper::setExpiredAt($user_service_plan, $user);
    $plan_content = $user_service_plan->service_plan ? $user_service_plan->service_plan->payload : null;
    if ($plan_content) {
      foreach ($plan_content as $item_uuid => $item_content) {
        $user_service_item = new UserServicePlanItem;
        $service_plan_item = ServicePlanItem::where('uuid', $item_uuid)->first();
        if (!$service_plan_item) {
          continue;
        }
        $_type = $service_plan_item->type;
        if ($_type == 'annual-times') {
          $user_service_item->total_count  = $item_content;
          $user_service_item->remain_count = $item_content;
          $user_service_item->expired_at   = Carbon::now()->addYears(1);
        } else if ($_type == 'times') {
          $user_service_item->total_count  = $item_content;
          $user_service_item->remain_count = $item_content;
        }
        $user_service_item->service_plan_id      = $model->service_plan_id;
        $user_service_item->user_service_plan_id = $user_service_plan->id;
        $user_service_item->service_plan_item_id = $service_plan_item->id;
        $user_service_item->content              = $item_content;
        $user_service_item->user_id              = $user->id;
        $user_service_item->save();
      }
    }
    if (config('stone.pin_card.register_complete_action')) {
      config('stone.pin_card.register_complete_action')::pin_card_register_complete_action($model, $user);
    }
    LogHelper::createWebLog($user->id, 'register', 'service_plan', $model->service_plan_id, \Request::ip(), $request->pin);
    return response()->json([
      'message' => 'successful registed.',
    ]);
  }

  /**
   * Export Excel Signedurl
   *
   */
  public function export_excel_signedurl(Request $request)
  {
    return ModelHelper::ws_ExportExcelSignedurlHandler($this, $request);
  }

  /**
   * Export Excel
   *
   */
  public function export_excel(Request $request)
  {
    $headings = [
      "id",
      "Created at",
      "Updated at",
      "PIN",
      "Plan",
      "Status",
      "User ID",
    ];

    return ModelHelper::ws_ExportExcelHandler(
      $this,
      $request,
      $headings,
      function ($model) {
        $created_at = Carbon::parse($model->created_at)->format('Y-m-d');
        $updated_at = Carbon::parse($model->updated_at)->format('Y-m-d');
        $map        = [
          $model->id,
          $created_at,
          $updated_at,
          $model->pin,
          $model->service_plan ? $model->service_plan->name : null,
          $model->status,
          $model->user_id,
        ];
        return $map;
      }
    );
  }
}
