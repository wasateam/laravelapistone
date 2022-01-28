<?php

namespace Wasateam\Laravelapistone\Controllers;

use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Http\Request;
use Wasateam\Laravelapistone\Helpers\ModelHelper;
use Wasateam\Laravelapistone\Models\ThePointRecord;

/**
 * @group ThePointRecord 點數紀錄
 *
 * APIs for the_point_record
 *
 * type 類型
 * ~ get 取得
 * ~ deduct 扣除
 * source 來源
 * ~ new_shop_order 新訂單
 * user 所綁定之使用者
 * shop_order 所綁定之訂單
 * shop_campagin 所綁定之促銷活動
 * count 數量
 *
 *
 * @authenticated
 */
class ThePointRecordController extends Controller
{
  public $model        = 'Wasateam\Laravelapistone\Models\ThePointRecord';
  public $name         = 'the_point_record';
  public $resource     = 'Wasateam\Laravelapistone\Resources\ThePointRecord';
  public $input_fields = [
    'type',
    'source',
    'count',
  ];
  public $belongs_to = [
    'user',
    'shop_order',
    'shop_campaign',
  ];
  public $filter_belongs_to = [
  ];
  public $uuid = false;

  public function __construct()
  {
    if (config('stone.mode') == 'cms') {
      $this->filter_belongs_to[] = 'user';
      $this->filter_belongs_to[] = 'shop_order';
      $this->filter_belongs_to[] = 'shop_campaign';
    }
  }

  /**
   * Index
   *
   * @queryParam user string No-example 1
   * @queryParam shop_order string No-example 1
   * @queryParam shop_campaign string No-example 1
   *
   */
  public function index(Request $request, $id = null)
  {
    return ModelHelper::ws_IndexHandler($this, $request, $id, );
  }

  /**
   * Auth Index 使用者點數記錄列表
   *
   * @queryParam get_all string No-example 1,0
   *
   */
  public function auth_index(Request $request, $id = null)
  {
    return ModelHelper::ws_IndexHandler($this, $request, $id, $request->get_all, function ($snap) {
      return $snap->where('user_id', Auth::user()->id);
    });
  }

  /**
   * Store
   *
   * @bodyParam type string Example:get
   * @bodyParam source string Example:new_shop_order
   * @bodyParam user int Example:1
   * @bodyParam shop_order int Example:1
   * @bodyParam shop_campaign int Example:1
   * @bodyParam count int Example:30
   */
  public function store(Request $request, $id = null)
  {
    return ModelHelper::ws_StoreHandler($this, $request, $id);
  }

  /**
   * Show
   *
   * @urlParam  the_point_record required The ID of the_point_record. Example: 1
   */
  public function show(Request $request, $id = null)
  {
    if (config('stone.mode' == 'webapi')) {
      $the_point_record = ThePointRecord::find($id);
      if (isset($the_point_record) && $the_point_record->user_id != Auth::user()->id) {
        return response()->json([
          'message' => 'not your record',
        ], 400);
      }
    }
    return ModelHelper::ws_ShowHandler($this, $request, $id);
  }

  /**
   * Update
   *
   * @urlParam  the_point_record required The ID of the_point_record. Example: 1
   * @bodyParam type string Example:get
   * @bodyParam source string Example:new_shop_order
   * @bodyParam user int Example:1
   * @bodyParam shop_order int Example:1
   * @bodyParam shop_campaign int Example:1
   * @bodyParam count int Example:30
   */
  public function update(Request $request, $id)
  {
    return ModelHelper::ws_UpdateHandler($this, $request, $id);
  }

  /**
   * Delete
   *
   * @urlParam  the_point_record required The ID of the_point_record. Example: 1
   */
  public function destroy($id)
  {
    return ModelHelper::ws_DestroyHandler($this, $id);
  }
}
