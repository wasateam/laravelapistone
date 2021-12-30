<?php

namespace Wasateam\Laravelapistone\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Wasateam\Laravelapistone\Helpers\ModelHelper;

/**
 * @group 促銷活動 ShopCampaign
 *
 * 促銷活動 API
 *
 * 類型 type
 * 活動名稱 name
 * 啟用狀態 is_active
 * 起始時間 start_date
 * 結束時間 end_date
 * 折扣碼 discount_code
 * 活動內容 condition
 * ~首購
 * ~不限 null
 * 滿額限制(金額) full_amount
 * 打折折數 discount_percent
 * 折抵金額 discount_amount
 *
 * Index 篩選
 * status
 * ~ 1 進行中 2 未開始 3 已結束
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
  ];
  public $search_fields = [
    'name',
  ];
  public $filter_fields = [
    'is_active',
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
   *
   */
  public function index(Request $request, $id = null)
  {
    return ModelHelper::ws_IndexHandler($this, $request, $id, $request->get_all, function ($snap) use ($request) {
      $date = ($request != null) && $request->filled('date') ? Carbon::parse($request->date) : null;
      if (isset($date)) {
        $snap = $snap->where(function ($query) use ($date) {
          $query->where('end_date', '>=', $date)->where('start_date', '<=', $date);
        });
      }
      $status = ($request != null) && $request->filled('status') ? $request->status : null;
      if (isset($status)) {
        $today = Carbon::now()->format('Y-m-d');
        if ($status == 1) {
          $snap = $snap->where(function ($query) use ($today) {
            $query->whereDate('end_date', '>=', $today)->whereDate('start_date', '<=', $today);
          })->orWhereNull('start_date');
        } else if ($status == 2) {
          $snap = $snap->whereDate('start_date', '>', $today);
        } else if ($status == 3) {
          $snap = $snap->whereDate('end_date', '<', $today);
        }
      }
      return $snap;
    });
  }

  /**
   * Store
   *
   * @bodyParam type string 活動類型 Example:type
   * @bodyParam name string 活動名稱 Example:name
   * @bodyParam start_date string 開始日期 Example:2021-10-10
   * @bodyParam end_date string 結束日期 Example:2021-10-20
   * @bodyParam dicount_code string 折扣碼 Example:134CODE
   * @bodyParam condition string Example:first purchase
   * @bodyParam full_amount int Example:100
   * @bodyParam discount_percent string Example:0-100
   * @bodyParam discount_amount int Example:100
   * @bodyParam is_active int Example:1
   * @bodyParam limit int Example:100
   */
  public function store(Request $request, $id = null)
  {
    return ModelHelper::ws_StoreHandler($this, $request, $id);
  }

  /**
   * Show
   *
   * @urlParam  shop_campaign required The ID of shop_campaign. Example: 1
   */
  public function show(Request $request, $id = null)
  {
    return ModelHelper::ws_ShowHandler($this, $request, $id);
  }

  /**
   * Update
   *
   * @urlParam  shop_campaign required The ID of shop_campaign. Example: 1
   * @bodyParam type string 活動類型 Example:type
   * @bodyParam name string 活動名稱 Example:name
   * @bodyParam start_date string 開始日期 Example:2021-10-10
   * @bodyParam end_date string 結束日期 Example:2021-10-20
   * @bodyParam dicount_code string 折扣碼 Example:134CODE
   * @bodyParam condition string Example:first purchase
   * @bodyParam full_amount int Example:100
   * @bodyParam discount_percent string Example:0-100
   * @bodyParam discount_amount int Example:100
   * @bodyParam is_active int Example:1
   * @bodyParam limit int Example:100
   */
  public function update(Request $request, $id)
  {
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

}