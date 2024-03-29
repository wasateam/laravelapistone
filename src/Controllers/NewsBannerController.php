<?php

namespace Wasateam\Laravelapistone\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Wasateam\Laravelapistone\Helpers\ModelHelper;

/**
 * @group NewsBanner 橫幅Banner
 *
 * api for news_banner
 *
 * name 後台管理識別用途
 * sq 排序設定值
 * ~ 數值越低、排序越前
 * bg_img_1440 底圖1440
 * bg_img_768 底圖768
 * bg_img_320 底圖320
 * link 連結
 * title 標題
 * title_color 標題顏色
 * des 說明
 * des_color 說明顏色
 * start_date 上架日期
 * end_date 下架日期
 * is_active 是否上架
 * page_settings 頁面設定
 *
 * @authenticated
 *
 */
class NewsBannerController extends Controller
{
  public $model              = 'Wasateam\Laravelapistone\Models\NewsBanner';
  public $name               = 'news_banner';
  public $resource           = 'Wasateam\Laravelapistone\Resources\NewsBanner';
  public $resource_for_order = 'Wasateam\Laravelapistone\Resources\NewsBanner_R_Order';
  public $input_fields       = [
    'name',
    'sq',
    'bg_img_1440',
    'bg_img_768',
    'bg_img_320',
    'link',
    'title',
    'title_color',
    'des',
    'des_color',
    'start_date',
    'end_date',
    'is_active',
  ];
  public $search_fields = [
    'name',
    'title',
  ];
  public $filter_fields = [
    'is_active',
  ];
  public $belongs_to = [
  ];
  public $belongs_to_many = [
    'page_settings',
  ];
  public $filter_belongs_to_many = [
    'page_settings',
  ];
  public $order_fields = [
    'sq',
  ];
  public $order_by = 'sq';

  public function __construct()
  {
  }

  /**
   * Index
   *
   * @queryParam search string 搜尋字串 No-example
   * @queryParam page int 頁碼(前台全抓)  No-example
   *
   */
  public function index(Request $request, $id = null)
  {
    if (config('stone.mode') == 'cms') {
      return ModelHelper::ws_IndexHandler($this, $request, $id);
    } else if (config('stone.mode') == 'webapi') {
      return ModelHelper::ws_IndexHandler($this, $request, $id, true);
    }
  }

  /**
   * Store
   *
   * @bodyParam name string 名稱 No-example
   * @bodyParam sq 排序設定值 Example:1
   * @bodyParam bg_img_1440  底圖1440 Example:""
   * @bodyParam bg_img_768 底圖768 Example:""
   * @bodyParam bg_img_320 底圖320 Example:""
   * @bodyParam link 連結 Example:""
   * @bodyParam title 標題 Example:Title
   * @bodyParam title_color 標題顏色 Example:#000
   * @bodyParam des 說明 Example:DesDesDes
   * @bodyParam des_color 說明顏色 Example:#000
   * @bodyParam start_date timestamp 上架日期 Example:2022-01-01 01:01:01
   * @bodyParam end_date timestamp 下架日期 Example:2022-01-05 01:01:01
   * @bodyParam is_active boolean 刊登狀態 Example:1
   * @bodyParam page_settings ids 頁面設定 Example:1,2

   */
  public function store(Request $request, $id = null)
  {
    return ModelHelper::ws_StoreHandler($this, $request, $id);
  }

  /**
   * Show
   *
   * @urlParam  news_banner required The ID of news_banner. Example: 1
   */
  public function show(Request $request, $id = null)
  {
    return ModelHelper::ws_ShowHandler($this, $request, $id);
  }

  /**
   * Update
   *
   * @urlParam  news_banner required The ID of news_banner. Example: 1
   * @bodyParam name string 名稱 No-example
   * @bodyParam sq 排序設定值 Example:1
   * @bodyParam bg_img_1440  底圖1440 Example:""
   * @bodyParam bg_img_768 底圖768 Example:""
   * @bodyParam bg_img_320 底圖320 Example:""
   * @bodyParam link 連結 Example:""
   * @bodyParam title 標題 Example:Title
   * @bodyParam title_color 標題顏色 Example:#000
   * @bodyParam des 說明 Example:DesDesDes
   * @bodyParam des_color 說明顏色 Example:#000
   * @bodyParam page_settings ids 頁面設定 Example:1,2

   */
  public function update(Request $request, $id)
  {
    return ModelHelper::ws_UpdateHandler($this, $request, $id);
  }

  /**
   * Delete
   *
   * @urlParam  news_banner required The ID of news_banner. Example: 2
   */
  public function destroy($id)
  {
    return ModelHelper::ws_DestroyHandler($this, $id);
  }

  /**
   * Order Get
   *
   */
  public function order_get(Request $request)
  {
    return ModelHelper::ws_OrderGetHandler($this, $request);
  }

  /**
   * Order Patch
   *
   */
  public function order_patch(Request $request)
  {
    return ModelHelper::ws_OrderPatchHandler($this, $request);
  }
}
