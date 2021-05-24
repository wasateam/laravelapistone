<?php

namespace Wasateam\Laravelapistone\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Wasateam\Laravelapistone\Helpers\ModelHelper;

/**
 * @group Locale
 *
 * @authenticated
 *
 * APIs for locale
 */
class LocaleController extends Controller
{
  public $model        = 'Wasateam\Laravelapistone\Models\Locale';
  public $name         = 'locale';
  public $resource     = 'Wasateam\Laravelapistone\Resources\Locale';
  public $input_fields = [
    'sequence',
    'code',
    'name',
  ];
  public $belongs_to = [
    'backup_locale',
  ];
  public $search_fields = [
    'name',
    'code',
  ];
  public $order_fields = [
    'sequence',
    'code',
    'name',
    'updated_at',
    'created_at',
  ];
  public $order_belongs_to = [
    'backup_locale',
  ];
  public $user_record_field = 'updated_admin_id';

  /**
   * Index
   *
   * @queryParam  search string Search on name,code match No-example
   *
   */
  public function index(Request $request, $id = null)
  {
    return ModelHelper::ws_IndexHandler($this, $request, $id);
  }

  /**
   * Store
   *
   * @bodyParam  sequence string Custom sequence field
   * @bodyParam  code string Example:jp
   * @bodyParam  name string Example:日文
   * @bodyParam  backup_locale int
   */
  public function store(Request $request, $id = null)
  {
    return ModelHelper::ws_StoreHandler($this, $request, $id);
  }

  /**
   * Show
   *
   * @urlParam  locale required The ID of locale. Example: 1
   */
  public function show(Request $request, $id = null)
  {
    return ModelHelper::ws_ShowHandler($this, $request, $id);
  }

  /**
   * Update
   *
   * @urlParam  locale required The ID of locale. Example: 1
   * @bodyParam  sequence string Custom sequence field
   * @bodyParam  code string Example:jp
   * @bodyParam  name string Example:日文
   * @bodyParam  backup_locale int
   */
  public function update(Request $request, $id)
  {
    return ModelHelper::ws_UpdateHandler($this, $request, $id);
  }

  /**
   * Delete
   *
   * @urlParam  locale required The ID of locale. Example: 2
   */
  public function destroy($id)
  {
    return ModelHelper::ws_DestroyHandler($this, $id);
  }
}
