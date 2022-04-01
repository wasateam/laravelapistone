<?php

namespace Wasateam\Laravelapistone\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Wasateam\Laravelapistone\Helpers\ModelHelper;

/**
 * @group WsBlogClass 部落格分類
 *
 * @authenticated
 *
 * name 名稱
 * type 部落格類型類型 (用於做不同文章模組)
 * 
 * 
 */
class WsBlogClassController extends Controller
{
  public $model        = 'Wasateam\Laravelapistone\Models\WsBlogClass';
  public $name         = 'ws_blog_class';
  public $resource     = 'Wasateam\Laravelapistone\Resources\WsBlogClass';
  public $input_fields = [
    'name',
  ];
  public $search_fields = [
    'name',
  ];
  public $filter_fields = [
    'type',
  ];

  /**
   * Index
   * @queryParam search string No-example
   * @queryParam type string No-example
   *
   */
  public function index(Request $request, $id = null)
  {
    return ModelHelper::ws_IndexHandler($this, $request, $id);
  }

  /**
   * Store
   *
   * @bodyParam name string No-example
   *
   */
  public function store(Request $request, $id = null)
  {
    return ModelHelper::ws_StoreHandler($this, $request, $id);
  }

  /**
   * Show
   *
   * @urlParam  blog required The ID of blog. Example: 1
   */
  public function show(Request $request, $id = null)
  {
    return ModelHelper::ws_ShowHandler($this, $request, $id);
  }

  /**
   * Update
   *
   * @urlParam  blog required The ID of blog. Example: 1
   * @bodyParam name string No-example
   *
   */
  public function update(Request $request, $id)
  {
    return ModelHelper::ws_UpdateHandler($this, $request, $id);
  }

  /**
   * Delete
   *
   * @urlParam  blog required The ID of blog. Example: 2
   */
  public function destroy($id)
  {
    return ModelHelper::ws_DestroyHandler($this, $id);
  }

}
