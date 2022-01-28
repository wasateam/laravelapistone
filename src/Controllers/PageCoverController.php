<?php

namespace Wasateam\Laravelapistone\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Wasateam\Laravelapistone\Helpers\ModelHelper;
use Wasateam\Laravelapistone\Helpers\ShopHelper;

/**
 * @group PageCover 彈跳視窗
 *
 * APIs for page_cover
 *
 * name 名稱
 * start_date 起始日期
 * end_date 結束日期
 * is_active
 * ~ 1 已上架
 * ~ 0 未上架
 * link 連結
 * image 圖片
 * page_settings 所屬頁面id
 * @authenticated
 */
class PageCoverController extends Controller
{
  public $model        = 'Wasateam\Laravelapistone\Models\PageCover';
  public $name         = 'page_cover';
  public $resource     = 'Wasateam\Laravelapistone\Resources\PageCover';
  public $input_fields = [
    'name',
    'start_date',
    'end_date',
    'is_active',
    'link',
    'image',
  ];
  public $uuid = false;

  public function __construct()
  {
    if (config('stone.page_setting')) {
      $this->belongs_to_many[]        = 'page_settings';
      $this->filter_belongs_to_many[] = 'page_settings';
    }
  }

  /**
   * Index
   *
   * @queryParam pages_settings string No-example
   *
   */
  public function index(Request $request, $id = null)
  {
    return ModelHelper::ws_IndexHandler($this, $request, $id);
  }

  /**
   * Store
   *
   * @bodyParam name string Example:name
   * @bodyParam start_date date Example:2022-02-10
   * @bodyParam end_date date Example:2022-05-10
   * @bodyParam is_active boolean Example:1
   * @bodyParam link string Example:"wasatema.com"
   * @bodyParam image string Example:""
   * @bodyParam page_settings object Example:[1,2,3]
   */
  public function store(Request $request, $id = null)
  {
    if ($request->start_date || $request->end_date) {
      $has_repeat = ShopHelper::samePageCoverDuration($request->start_date, $request->end_date, null, $request->page_settings);
      if ($has_repeat) {
        return response()->json([
          'message' => 'this date already exist.',
        ], 400);
      }
    }
    return ModelHelper::ws_StoreHandler($this, $request, $id);
  }

  /**
   * Show
   *
   * @urlParam  page_cover required The ID of page_cover. Example: 1
   */
  public function show(Request $request, $id = null)
  {
    return ModelHelper::ws_ShowHandler($this, $request, $id);
  }

  /**
   * Update
   *
   * @urlParam  page_cover required The ID of page_cover. Example: 1
   * @bodyParam name string Example:name
   * @bodyParam start_date date Example:2022-02-10
   * @bodyParam end_date date Example:2022-05-10
   * @bodyParam is_active boolean Example:1
   * @bodyParam link string Example:"wasatema.com"
   * @bodyParam image string Example:""
   * @bodyParam page_settings object Example:[1,2,3]
   */
  public function update(Request $request, $id)
  {
    if ($request->start_date || $request->end_date) {
      $has_repeat = ShopHelper::samePageCoverDuration($request->start_date, $request->end_date, $id, $request->page_settings);
      if ($has_repeat) {
        return response()->json([
          'message' => 'this date already exist.',
        ], 400);
      }
    }
    return ModelHelper::ws_UpdateHandler($this, $request, $id);
  }

  /**
   * Delete
   *
   * @urlParam  page_setting required The ID of page_setting. Example: 1
   */
  public function destroy($id)
  {
    return ModelHelper::ws_DestroyHandler($this, $id);
  }
}
