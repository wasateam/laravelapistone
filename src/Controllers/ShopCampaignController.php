<?php

namespace Wasateam\Laravelapistone\Controllers;

use App\Http\Controllers\Controller;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Wasateam\Laravelapistone\Helpers\ModelHelper;
use Wasateam\Laravelapistone\Helpers\ShopHelper;
use Wasateam\Laravelapistone\Models\ShopCampaign;

/**
 * @group ShopCampaign 促銷活動
 *
 * 促銷活動 API
 *
 * 類型 type
 * ~ 紅利點數回饋 bonus_point_feedback
 * ~ 折扣碼 discount_code
 * ~ 會員邀請 member_invite
 * 活動名稱 name
 * 啟用狀態 is_active
 * 起始時間 start_date
 * 結束時間 end_date
 * 折扣碼 discount_code
 * 活動內容 condition
 * ~ 首購 first-purchase
 * ~ 不限 null
 * ~紅利點數回饋 比例 rate
 * 滿額限制(金額) full_amount
 * 打折折數 discount_percent
 * 折抵金額 discount_amount
 * 回饋比例 feedback_rate
 * 是否啟用 is_active
 * 數量 limit
 * 折扣方式 discount_way
 *
 * Index 篩選
 * status
 * ~ in-progress 進行中
 * ~ not-started 未開始
 * ~ end 已結束
 *
 * Store,Update相關補充
 * 需要送condition，condition要送什麼會依據type而不同
 * ex.紅利點數回饋如果是用比例的話，condition送rate
 *
 * @authenticated
 */

class ShopCampaignController extends Controller
{
  public $model                   = 'Wasateam\Laravelapistone\Models\ShopCampaign';
  public $name                    = 'shop_campaign';
  public $resource                = 'Wasateam\Laravelapistone\Resources\ShopCampaign';
  public $resource_for_collection = 'Wasateam\Laravelapistone\Resources\ShopCampaignCollection';
  public $input_fields            = [
    'type',
    'name',
    'start_date',
    'end_date',
    'discount_code',
    'condition',
    'full_amount',
    'discount_percent',
    'discount_amount',
    'limit',
    'is_active',
    'feedback_rate',
    'discount_way',
  ];
  public $search_fields = [
    'name',
    'discount_code',
  ];
  public $filter_fields = [
    'is_active',
    'type',
  ];
  public $order_fields = [
    "start_date",
    "end_date",
    "created_at",
    "updated_at",
  ];
  public $time_fields = [
    "start_date",
    "end_date",
    "created_at",
    "updated_at",
  ];

  /**
   * Index
   * @queryParam search string 搜尋  No-example
   * @queryParam date string 篩選時間日期 No-example
   * @queryParam order_way string No-example asc,desc
   * @queryParam order_by string  No-example created_at,updated_at,start_date,end_date
   * @queryParam start_time string No-example 2021-10-20
   * @queryParam end_time string No-example 2021-10-30
   * @queryParam time_field string  No-example created_at,updated_at
   * @queryParam is_active int  No-example 0,1
   * @queryParam status int  No-example 1,2,3
   * @queryParam type string  No-example bonus_point_feedback,discount_code
   *
   */
  public function index(Request $request, $id = null)
  {
    return ModelHelper::ws_IndexHandler($this, $request, $id, $request->get_all, function ($snap) use ($request) {
      //篩選日期區間
      $date = ($request != null) && $request->filled('date') ? Carbon::parse($request->date) : null;
      if (isset($date)) {
        $snap = $snap->where(function ($query) use ($date) {
          $query->where('end_date', '>=', $date)->where('start_date', '<=', $date);
        });
      }
      //篩選狀態
      $status = ($request != null) && $request->filled('status') ? $request->status : null;
      if (isset($status)) {
        $today = Carbon::now()->format('Y-m-d');
        if ($status == 'in-progress') {
          $snap = $snap->where(function ($query) use ($today) {
            $query->whereDate('end_date', '>=', $today)->whereDate('start_date', '<=', $today);
            $query->orWhereNull('start_date');
          });
        } else if ($status == 'not-started') {
          $snap = $snap->whereDate('start_date', '>', $today);
        } else if ($status == 'end') {
          $snap = $snap->whereDate('end_date', '<', $today);
        }
      }
      return $snap;
    });
  }

  /**
   * Store
   *
   * @bodyParam type string 活動類型 Example:new_feedback new_feedback,bonus_point_feedback
   * @bodyParam name string 活動名稱 Example:name
   * @bodyParam start_date string 開始日期 Example:2021-10-10
   * @bodyParam end_date string 結束日期 Example:2021-10-20
   * @bodyParam discount_code string 折扣碼 Example:134CODE
   * @bodyParam condition string Example:first purchase
   * @bodyParam full_amount int Example:100
   * @bodyParam discount_percent string Example:0-100
   * @bodyParam discount_amount int Example:100
   * @bodyParam is_active int Example:1
   * @bodyParam limit int Example:100
   * @bodyParam feedback_rate float Example:0.001
   * @bodyParam discount_way string Example:discount_way
   */
  public function store(Request $request, $id = null)
  {
    if (!$request->type) {
      return response()->json([
        'message' => 'type is required.',
      ], 400);
    }
    if ($request->has('type')) {
      //get types from stone.php
      $types    = config('stone.shop.shop_campaign.types');
      $req_type = $request->type;
      $has_key  = array_key_exists($req_type, $types);
      if ($has_key) {
        $type = $types[$req_type] ? $types[$req_type] : null;
        //date_no_repeat
        if ($types[$req_type]['date_no_repeat']) {
          $has_repeat = ShopHelper::sameCampaignDuration($request->start_date, $request->end_date, null, $request->type);
          if ($has_repeat) {
            return response()->json([
              'message' => 'this date already exist.',
            ], 400);
          }
        }
      }
    }
    return ModelHelper::ws_StoreHandler($this, $request, $id);
  }

  /**
   * Show
   *
   * @urlParam  shop_campaign required The ID of shop_campaign. Example: 1
   * @queryParam status string  No-example progressing,non-start,end
   */
  public function show(Request $request, $id = null)
  {
    return ModelHelper::ws_ShowHandler($this, $request, $id, function ($snap) use ($request) {
      //篩選狀態
      $status = $request->has('status') ? $request->status : null;
      if (isset($status)) {
        $today = Carbon::now()->format('Y-m-d');
        if ($status == 'in-progress') {
          $snap = $snap->where(function ($query) use ($today) {
            $query->whereDate('end_date', '>=', $today)->whereDate('start_date', '<=', $today);
          })->orWhereNull('start_date');
        } else if ($status == 'not-started') {
          $snap = $snap->whereDate('start_date', '>', $today);
        } else if ($status == 'end') {
          $snap = $snap->whereDate('end_date', '<', $today);
        }
      }
      return $snap;
    });
  }

  /**
   * Update
   *
   * @urlParam  shop_campaign required The ID of shop_campaign. Example: 1
   * @bodyParam type string 活動類型 Example:type
   * @bodyParam name string 活動名稱 Example:name
   * @bodyParam start_date string 開始日期 Example:2021-10-10
   * @bodyParam end_date string 結束日期 Example:2021-10-20
   * @bodyParam discount_code string 折扣碼 Example:134CODE
   * @bodyParam condition string Example:first purchase
   * @bodyParam full_amount int Example:100
   * @bodyParam discount_percent string Example:0-100
   * @bodyParam discount_amount int Example:100
   * @bodyParam is_active int Example:1
   * @bodyParam limit int Example:100
   * @bodyParam feedback_rate float Example:0.001
   * @bodyParam discount_way string Example:discount_way
   */
  public function update(Request $request, $id)
  {
    if (!$request->type) {
      return response()->json([
        'message' => 'type is required.',
      ], 400);
    }
    if ($request->has('type')) {
      //get types from stone.php
      $types    = config('stone.shop.shop_campaign.types');
      $req_type = $request->type;
      // $req_type = str_replace('-', '_', $request->type);
      $has_key = array_key_exists($req_type, $types);
      if ($has_key) {
        $type = $types[$req_type] ? $types[$req_type] : null;
        //date_no_repeat
        if (isset($type['date_no_repeat'])) {
          $has_repeat = ShopHelper::sameCampaignDuration($request->start_date, $request->end_date, $id, $request->type);
          if ($has_repeat) {
            return response()->json([
              'message' => 'this date already exist.',
            ], 400);
          }
        }
      }
    }
    return ModelHelper::ws_UpdateHandler($this, $request, $id);
  }

  /**
   * Delete
   *
   * @urlParam  shop_campaign required The ID of shop_campaign. Example: 2
   */
  public function destroy($id)
  {
    return ModelHelper::ws_DestroyHandler($this, $id);
  }

  /**
   * Today DiscountCode Get 取得今日折扣碼活動
   *
   * @queryParam user int user_id Example:1
   * @bodyParam discount_code string 折扣碼 Example:LittleChicken
   */
  public function get_today_discount_code(Request $request)
  {
    $request->validate([
      'discount_code' => 'required',
    ]);
    if (!$request->has('discount_code')) {
      throw new \Wasateam\Laravelapistone\Exceptions\FieldRequiredException('discount_code');
    }
    $user          = Auth::user();
    $shop_campaign = ShopHelper::getAvailableShopCampaign('discount_code', $user->id, null, $request->discount_code);

    if (!$shop_campaign) {
      throw new \Wasateam\Laravelapistone\Exceptions\FindNoDataException('shop_campaign');
    }
    return new $this->resource($shop_campaign);
  }

  /**
   * Today Index 今日促銷活動
   * @queryParam search string 搜尋  No-example
   * @queryParam type string  No-example bonus_point_feedback,discount_code
   * @queryParam type string  No-example bonus_point_feedback,discount_code
   *
   */
  public function today_index(Request $request, $id = null)
  {
    return ModelHelper::ws_IndexHandler($this, $request, $id, $request->get_all, function ($snap) {
      //today
      $today_date = Carbon::now()->format('Y-m-d');
      //篩選日期區間
      $snap = $snap->where('end_date', '>=', $today_date)->where('start_date', '<=', $today_date)->where('is_active', 1);
      return $snap;
    });
  }
}
