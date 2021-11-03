<?php

namespace Wasateam\Laravelapistone\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Wasateam\Laravelapistone\Helpers\ModelHelper;

/**
 * @group WsBlog
 *
 * @authenticated
 *
 * APIs for ws_blog
 */
class WsBlogController extends Controller
{
  public $model                   = 'Wasateam\Laravelapistone\Models\WsBlogClass';
  public $name                    = 'ws_blog_class';
  public $resource                = 'Wasateam\Laravelapistone\Resources\WsBlogClass';
  public $resource_for_collection = 'Wasateam\Laravelapistone\Resources\WsBlogClassCollection';
  public $input_fields            = [
    'id',
    'name',
  ];
  public $search_fields = [
    'name',
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
