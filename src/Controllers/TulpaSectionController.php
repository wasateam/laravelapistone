<?php

namespace Wasateam\Laravelapistone\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Wasateam\Laravelapistone\Helpers\ModelHelper;
use Wasateam\Laravelapistone\Models\TulpaSection;

/**
 * @group TulpaSection
 *
 * @authenticated
 *
 * APIs for tulpa_section
 */
class TulpaSectionController extends Controller
{
  public $model                   = 'Wasateam\Laravelapistone\Models\TulpaSection';
  public $name                    = 'tulpa_section';
  public $resource                = 'Wasateam\Laravelapistone\Resources\TulpaSection';
  public $resource_for_collection = 'Wasateam\Laravelapistone\Resources\TulpaSectionCollection';
  public $input_fields            = [
    'name',
    'component_name',
    'remark',
    'fields',
    'content',
    'tags',
  ];
  public $search_fields = [
    'name',
    'tags',
  ];
  public $order_fields = [
    'updated_at',
    'created_at',
    'name',
    'id',
  ];
  public $user_record_field = 'updated_admin_id';
  public $user_create_field = 'created_admin_id';

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
   * @bodyParam name string Example: My Page 1
   * @bodyParam component_name string Example: MyPage001
   * @bodyParam tags object Example: ["tag 1","tag 2"]
   * @bodyParam remark string Example: Remark for admins
   * @bodyParam content object No-example
   * @bodyParam fields object No-example
   */
  public function store(Request $request, $id = null)
  {
    return ModelHelper::ws_StoreHandler($this, $request, $id);
  }

  /**
   * Show
   *
   * @urlParam  tulpa_section required The ID of tulpa_section. Example: 1
   */
  public function show(Request $request, $id = null)
  {
    return ModelHelper::ws_ShowHandler($this, $request, $id);
  }

  /**
   * Update
   *
   * @urlParam  tulpa_section required The ID of tulpa_section. Example: 1
   * @bodyParam name string Example: My Page 1
   * @bodyParam component_name string Example: MyPage001
   * @bodyParam tags object Example: ["tag 1","tag 2"]
   * @bodyParam remark string Example: Remark for admins
   * @bodyParam content object No-example
   * @bodyParam fields object No-example
   */
  public function update(Request $request, $id)
  {
    return ModelHelper::ws_UpdateHandler($this, $request, $id);
  }

  /**
   * Delete
   *
   * @urlParam  tulpa_section required The ID of tulpa_section. Example: 2
   */
  public function destroy($id)
  {
    return ModelHelper::ws_DestroyHandler($this, $id);
  }
}
