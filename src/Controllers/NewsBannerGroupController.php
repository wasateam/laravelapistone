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
 * @authenticated
 */
class NewsBannerGroupController extends Controller
{
  public $model        = 'Wasateam\Laravelapistone\Models\NewsBannerGroup';
  public $name         = 'news_benner_group';
  public $resource     = 'Wasateam\Laravelapistone\Resources\NewsBannerGroup';
  public $input_fields = [
    'name',
    'sq',
  ];
  public $order_fields = [
    'sq',
  ];
  public $order_by = 'sq';

  public function __contrust()
  {
    if (config('stone.page_setting')) {
      $this->belongs_to_many[]        = 'page_settings';
      $this->filter_belongs_to_many[] = 'page_settings';
    }
  }

  /**
   * Index
   * @queryParam search string No-example
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
   * News Banner Order Get
   *
   */
  public function news_banner_order_get($id)
  {
    return ModelHelper::ws_BelongsToManyOrderGetHandler($id, $this, 'news_banners', 'Wasateam\Laravelapistone\Resources\NewsBanner');
  }

  /**
   * News Banner Order Patch
   *
   * @bodyParam order object Example:[{id:1}]
   */
  public function news_banner_order_patch($id, Request $request)
  {
    return ModelHelper::ws_BelongsToManyOrderPatchHandler($id, $this, 'news_banners', $request);
  }
}
