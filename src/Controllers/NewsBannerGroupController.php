<?php

namespace Wasateam\Laravelapistone\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Wasateam\Laravelapistone\Helpers\ModelHelper;

/**
 * @group NewsBannerGroup Banner群組
 *
 * APIs for news_banner_group
 *
 * name 名稱
 * sq 排序
 * page_settings 所屬頁面id
 *
 * api ----
 * News Banner Order Get 取得NewsBanner(橫幅)在NewsBannerGroup裡面的排序
 * News Banner Order Patch 更新NewsBanner(橫幅)在NewsBannerGroup裡面的排序，送的是NewsBanner的id
 *
 * @authenticated
 */
class NewsBannerGroupController extends Controller
{
  public $model              = 'Wasateam\Laravelapistone\Models\NewsBannerGroup';
  public $name               = 'news_benner_group';
  public $resource           = 'Wasateam\Laravelapistone\Resources\NewsBannerGroup';
  public $resource_for_order = 'Wasateam\Laravelapistone\Resources\NewsBannerGroup_R_Order';
  public $input_fields       = [
    'name',
    'sq',
  ];
  public $order_fields = [
    'sq',
  ];
  public $order_by             = 'sq';
  public $order_layers_setting = [
    [
      'model' => 'Wasateam\Laravelapistone\Models\NewsBanner',
      'key'   => 'news_banners',
    ],
  ];

  public function __construct()
  {
    if (config('stone.page_setting')) {
      $this->belongs_to_many[]        = 'page_settings';
      $this->filter_belongs_to_many[] = 'page_settings';
    }
  }

  /**
   * Index
   * @queryParam search string No-example
   * @queryParam page_settings ids No-example 1,2,3
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
   * @bodyParam sq string Example:1
   * @bodyParam page_settings object Example:[1,2,3]
   */
  public function store(Request $request, $id = null)
  {
    return ModelHelper::ws_StoreHandler($this, $request, $id);
  }

  /**
   * Show
   *
   * @urlParam  news_banner_group required The ID of news_banner_group. Example: 1
   */
  public function show(Request $request, $id = null)
  {
    return ModelHelper::ws_ShowHandler($this, $request, $id);
  }

  /**
   * Update
   *
   * @urlParam  news_banner_group required The ID of news_banner_group. Example: 1
   * @bodyParam name string Example:name
   * @bodyParam sq string Example:1
   * @bodyParam page_settings object Example:[1,2,3]
   */
  public function update(Request $request, $id)
  {
    return ModelHelper::ws_UpdateHandler($this, $request, $id);
  }

  /**
   * Delete
   *
   * @urlParam  news_banner_group required The ID of news_banner_group. Example: 2
   */
  public function destroy($id)
  {
    return ModelHelper::ws_DestroyHandler($this, $id);
  }

  /**
   * News Banner Order Get 取得橫幅排序
   *
   */
  public function news_banner_order_get($id)
  {
    return ModelHelper::ws_BelongsToManyOrderGetHandler($id, $this, 'news_banners', 'Wasateam\Laravelapistone\Resources\NewsBanner');
  }

  /**
   * News Banner Order Patch 更新橫幅排序
   *
   * @bodyParam order object Example:[{"id":1}]
   */
  public function news_banner_order_patch($id, Request $request)
  {
    return ModelHelper::ws_BelongsToManyOrderPatchHandler($id, $this, 'news_banners', $request, 'news_banner_sq');
  }
}
