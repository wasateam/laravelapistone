<?php

namespace Wasateam\Laravelapistone\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Wasateam\Laravelapistone\Helpers\ModelHelper;

/**
 * @group DownloadInfo
 *
 * @authenticated
 *
 * clone_type clone 類型
 * url 網址
 * year 年份
 * name 名稱
 */
class DownloadInfoController extends Controller
{
  public $model        = 'Wasateam\Laravelapistone\Models\DownloadInfo';
  public $name         = 'download_info';
  public $resource     = 'Wasateam\Laravelapistone\Resources\DownloadInfo';
  public $input_fields = [
    'clone_type',
    'url',
    'year',
    'name',
  ];
  public $order_fields = [
    'updated_at',
    'created_at',
  ];
  public $filter_fields = [
    'clone_type',
    'year',
  ];
  public $search_fields = [
    'name',
  ];

  public function __construct()
  {
  }

  /**
   * Index
   * @queryParam search string No-example
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
   * @bodyParam info_type string No-example
   * @bodyParam url string No-example
   * @bodyParam year string No-example
   * @bodyParam name string No-example
   */
  public function store(Request $request, $id = null)
  {
    return ModelHelper::ws_StoreHandler($this, $request, $id);
  }

  /**
   * Show
   *
   * @urlParam  download_info required The ID of download_info. Example: 1
   */
  public function show(Request $request, $id = null)
  {
    return ModelHelper::ws_ShowHandler($this, $request, $id);
  }

  /**
   * Update
   *
   * @urlParam  download_info required The ID of download_info. Example: 1
   * @bodyParam info_type string No-example
   * @bodyParam url string No-example
   * @bodyParam year string No-example
   * @bodyParam name string No-example
   */
  public function update(Request $request, $id)
  {
    return ModelHelper::ws_UpdateHandler($this, $request, $id);
  }

  /**
   * Delete
   *
   * @urlParam  download_info required The ID of download_info. Example: 2
   */
  public function destroy($id)
  {
    return ModelHelper::ws_DestroyHandler($this, $id);
  }
}
