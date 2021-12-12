<?php

namespace Wasateam\Laravelapistone\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Wasateam\Laravelapistone\Helpers\ModelHelper;

/**
 * @group 最新消息Banner
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
  ];
  public $belongs_to = [
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
   * @bodyParam bg_img_1440  底圖1440 No-example
   * @bodyParam bg_img_768 底圖768 No-example
   * @bodyParam bg_img_320 底圖320 No-example
   * @bodyParam link 連結 No-example
   * @bodyParam title 標題 Example:Title
   * @bodyParam title_color 標題顏色 Example:#000
   * @bodyParam des 說明 Example:DesDesDes
   * @bodyParam des_color 說明顏色 Example:#000

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
   * @bodyParam bg_img_1440  底圖1440 No-example
   * @bodyParam bg_img_768 底圖768 No-example
   * @bodyParam bg_img_320 底圖320 No-example
   * @bodyParam link 連結 No-example
   * @bodyParam title 標題 Example:Title
   * @bodyParam title_color 標題顏色 Example:#000
   * @bodyParam des 說明 Example:DesDesDes
   * @bodyParam des_color 說明顏色 Example:#000
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
}
