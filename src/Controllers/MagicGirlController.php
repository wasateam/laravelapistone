<?php

namespace Wasateam\Laravelapistone\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Wasateam\Laravelapistone\Helpers\ModelHelper;
use Wasateam\Laravelapistone\Models\MagicGirl;

/**
 * @group MagicGirl 魔法少女(範例資料)
 *
 * @authenticated
 *
 * name 明稱
 * sq 排序設定
 */
class MagicGirlController extends Controller
{
  public $model                   = 'Wasateam\Laravelapistone\Models\MagicGirl';
  public $name                    = 'magic_girl';
  public $resource                = 'Wasateam\Laravelapistone\Resources\MagicGirl';
  public $resource_for_collection = 'Wasateam\Laravelapistone\Resources\MagicGirlCollection';
  public $input_fields            = [
    'name',
    'sq',
  ];
  public $order_fields = [
    'updated_at',
    'created_at',
  ];
  public $country_code = true;
  public $paginate     = 30;

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
    return ModelHelper::ws_IndexHandler($this, $request, $id, true);
  }

  /**
   * Store
   *
   * @bodyParam name string No-example
   * @bodyParam sq string No-example
   */
  public function store(Request $request, $id = null)
  {
    return ModelHelper::ws_StoreHandler($this, $request, $id);
  }

  /**
   * Show
   *
   * @urlParam  magic_girl required The ID of magic_girl. Example: 1
   */
  public function show(Request $request, $id = null)
  {
    return ModelHelper::ws_ShowHandler($this, $request, $id);
  }

  /**
   * Update
   *
   * @urlParam  magic_girl required The ID of magic_girl. Example: 1
   * @bodyParam name string No-example
   * @bodyParam sq string No-example
   */
  public function update(Request $request, $id)
  {
    return ModelHelper::ws_UpdateHandler($this, $request, $id);
  }

  /**
   * Delete
   *
   * @urlParam  magic_girl required The ID of magic_girl. Example: 2
   */
  public function destroy($id)
  {
    return ModelHelper::ws_DestroyHandler($this, $id);
  }
}
