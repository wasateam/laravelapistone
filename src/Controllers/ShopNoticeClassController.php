<?php

namespace Wasateam\Laravelapistone\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Wasateam\Laravelapistone\Helpers\ModelHelper;

/**
 * @group Wsshop_notice_classClass
 *
 * @authenticated
 *
 * APIs for shop_notice_class
 */
class ShopNoticeClassController extends Controller
{
  public $model        = 'Wasateam\Laravelapistone\Models\ShopNoticeClass';
  public $name         = 'shop_notice_class';
  public $resource     = 'Wasateam\Laravelapistone\Resources\ShopNoticeClass';
  public $input_fields = [
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
   * @urlParam  shop_notice_class required The ID of shop_notice_class. Example: 1
   */
  public function show(Request $request, $id = null)
  {
    return ModelHelper::ws_ShowHandler($this, $request, $id);
  }

  /**
   * Update
   *
   * @urlParam  shop_notice_class required The ID of shop_notice_class. Example: 1
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
   * @urlParam  shop_notice_class required The ID of shop_notice_class. Example: 2
   */
  public function destroy($id)
  {
    return ModelHelper::ws_DestroyHandler($this, $id);
  }
}
