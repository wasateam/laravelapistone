<?php

namespace Wasateam\Laravelapistone\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Wasateam\Laravelapistone\Helpers\ModelHelper;

/**
 * @group Tag
 *
 * @authenticated
 *
 * name Tag名稱
 * target 目標 Model 資料類型
 *
 * APIs for tag
 */
class TagController extends Controller
{
  public $model                   = 'Wasateam\Laravelapistone\Models\Tag';
  public $name                    = 'tag';
  public $resource                = 'Wasateam\Laravelapistone\Resources\Tag';
  public $resource_for_collection = 'Wasateam\Laravelapistone\Resources\TagCollection';
  public $input_fields            = [
    'name',
    'target',
  ];
  public $belongs_to = [
  ];
  public $order_fields = [
    'id',
    'name',
    'updated_at',
    'created_at',
  ];

  /**
   * Index
   * @queryParam search string No-example
   *
   */
  public function index(Request $request, $id = null)
  {
    return ModelHelper::ws_IndexHandler($this, $request, $id);
  }

  /**
   * Store
   *
   * @bodyParam name string Example: 我是大
   * @bodyParam target string Example: User
   */
  public function store(Request $request, $id = null)
  {
    return ModelHelper::ws_StoreHandler($this, $request, $id);
  }

  /**
   * Show
   *
   * @urlParam  tag required The ID of tag. Example: 1
   */
  public function show(Request $request, $id = null)
  {
    return ModelHelper::ws_ShowHandler($this, $request, $id);
  }

  /**
   * Update
   *
   * @urlParam  tag required The ID of tag. Example: 1
   * @bodyParam name string Example: 我是大
   * @bodyParam target string Example: User
   */
  public function update(Request $request, $id)
  {
    return ModelHelper::ws_UpdateHandler($this, $request, $id);
  }

  /**
   * Delete
   *
   * @urlParam  tag required The ID of tag. Example: 2
   */
  public function destroy($id)
  {
    return ModelHelper::ws_DestroyHandler($this, $id);
  }
}
