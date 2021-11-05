<?php

namespace Wasateam\Laravelapistone\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Wasateam\Laravelapistone\Helpers\ModelHelper;

/**
 * @group GeneralContent
 *
 * @authenticated
 *
 * APIs for general_content
 */
class GeneralContentController extends Controller
{
  public $model        = 'Wasateam\Laravelapistone\Models\GeneralContent';
  public $name         = 'general_content';
  public $resource     = 'Wasateam\Laravelapistone\Resources\GeneralContent';
  public $input_fields = [
    'name',
    'content',
  ];
  public $belongs_to = [
  ];
  public $filter_belongs_to = [
  ];
  public $order_fields = [
    'updated_at',
    'created_at',
  ];

  /**
   * Index
   *
   * @queryParam  search string Search on locales.name match No-example
   * @urlParam area int Example:1
   *
   */
  public function index(Request $request, $id = null)
  {
    return ModelHelper::ws_IndexHandler($this, $request, $id, true);
  }

  /**
   * Store
   *
   * @bodyParam  sequence string Example:1
   * @bodyParam  start_time string Example: 1000
   * @bodyParam  end_time string Example: 1200
   * @bodyParam  service_store string Example: 1
   */
  public function store(Request $request, $id = null)
  {
    return ModelHelper::ws_StoreHandler($this, $request, $id);
  }

  /**
   * Show
   *
   * @urlParam  general_content required The ID of general_content. Example: 1
   */
  public function show(Request $request, $id = null)
  {
    return ModelHelper::ws_ShowHandler($this, $request, $id);
  }

  /**
   * Update
   *
   * @urlParam  general_content required The ID of general_content. Example: 1
   * @bodyParam  start_time string Example: 1000
   * @bodyParam  end_time string Example: 1200
   * @bodyParam  service_store string Example: 1
   */
  public function update(Request $request, $id)
  {
    return ModelHelper::ws_UpdateHandler($this, $request, $id);
  }

  /**
   * Delete
   *
   * @urlParam  general_content required The ID of general_content. Example: 2
   */
  public function destroy($id)
  {
    return ModelHelper::ws_DestroyHandler($this, $id);
  }
}
