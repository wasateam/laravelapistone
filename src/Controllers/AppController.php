<?php

namespace Wasateam\Laravelapistone\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Wasateam\Laravelapistone\Controllers\WsModelController;
use Wasateam\Laravelapistone\Helpers\ModelHelper;

/**
 * @group  App
 *
 * APIs for app
 *
 * @authenticated
 */
class AppController extends Controller
{
  public $model        = 'Wasateam\Laravelapistone\Models\App';
  public $name         = 'app';
  public $resource     = 'Wasateam\Laravelapistone\Resources\App';
  public $input_fields = [
    'key',
    'name',
    'url',
    'description',
    'avatar',
    'is_public',
  ];
  public $belongs_to = [
  ];
  public $filter_belongs_to = [
  ];
  public $order_fields = [
    'updated_at',
    'created_at',
  ];
  public $search_fields = [
    'name',
  ];

  /**
   * Index
   *
   * @queryParam search string No-Example name
   */
  public function index(Request $request, $id = null)
  {
    return ModelHelper::ws_IndexHandler($this, $request, $id);
  }

  /**
   * Store
   *
   * @bodyParam title string Example: AAAAA
   * @bodyParam key string Example: AAAA
   * @bodyParam name string Example: AAAA
   * @bodyParam url string Example: AAAA
   * @bodyParam description string Example: AAAA
   * @bodyParam avatar string Example: AAAA
   * @bodyParam is_public string Example: 0
   */
  public function store(Request $request, $id = null)
  {
    return ModelHelper::ws_StoreHandler($this, $request, $id);
  }

  /**
   * Show
   *
   * @urlParam  app required The ID of app. Example: 1
   */
  public function show(Request $request, $id = null)
  {
    return ModelHelper::ws_ShowHandler($this, $request, $id);
  }

  /**
   * Update
   *
   * @urlParam  app required The ID of app. Example: 1
   * @bodyParam title string Example: AAAAA
   * @bodyParam key string Example: AAAA
   * @bodyParam name string Example: AAAA
   * @bodyParam url string Example: AAAA
   * @bodyParam description string Example: AAAA
   * @bodyParam avatar string Example: AAAA
   * @bodyParam is_public string Example: 0
   */
  public function update(Request $request, $id)
  {
    return ModelHelper::ws_UpdateHandler($this, $request, $id);
  }

  /**
   * Delete
   *
   * @urlParam  app required The ID of app. Example: 2
   */
  public function destroy($id)
  {
    return ModelHelper::ws_DestroyHandler($this, $id);
  }
}
