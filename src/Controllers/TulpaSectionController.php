<?php

namespace Wasateam\Laravelapistone\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Wasateam\Laravelapistone\Helpers\ModelHelper;

/**
 * @group TulpaSection
 *
 * @authenticated
 *
 * APIs for tulpa_section
 */
class TulpaSectionController extends Controller
{
  public $model        = 'Wasateam\Laravelapistone\Models\TulpaSection';
  public $name         = 'tulpa_section';
  public $resource     = 'Wasateam\Laravelapistone\Resources\TulpaSection';
  public $input_fields = [
    'name',
    'content',
    'tags',
    'remark',
  ];
  public $belongs_to = [
    'tulpa_section_template',
  ];
  public $user_record_field = 'updated_admin_id';

  /**
   * Index
   * @urlParam search string No-example
   *
   */
  public function index(Request $request, $id = null)
  {
    return ModelHelper::ws_IndexHandler($this, $request, $id);
  }

  /**
   * Store
   *
   * @bodyParam name string Example: Section Name
   * @bodyParam content object No-example
   * @bodyParam tags object Example: ["tag 1", "tag 2"]
   * @bodyParam remark string Example: Remark
   * @bodyParam tulpa_section_template int Example:1
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
   * @bodyParam name string Example: Section Name
   * @bodyParam content object No-example
   * @bodyParam tags object Example: ["tag 1", "tag 2"]
   * @bodyParam remark string Example: Remark
   * @bodyParam tulpa_section_template int Example:1
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
