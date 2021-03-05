<?php

namespace Wasateam\Laravelapistone\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Wasateam\Laravelapistone\Helpers\ModelHelper;

/**
 * @group TulpaSectionTemplate
 *
 * @authenticated
 *
 * APIs for tulpa_section_template
 */
class TulpaSectionTemplateController extends Controller
{
  public $model        = 'Wasateam\Laravelapistone\Models\TulpaSectionTemplate';
  public $name         = 'tulpa_section_template';
  public $resource     = 'Wasateam\Laravelapistone\Resources\TulpaSectionTemplate';
  public $input_fields = [
    'name',
    'component_name',
    'remark',
    'fields',
  ];
  public $search_fields = [
    'name',
    'component_name',
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
   * @bodyParam name string Example: Tulpa Section Template
   * @bodyParam component_name string Example: ComponantName
   * @bodyParam remark string Example: Remark
   * @bodyParam fields object No-example
   */
  public function store(Request $request, $id = null)
  {
    return ModelHelper::ws_StoreHandler($this, $request, $id);
  }

  /**
   * Show
   *
   * @urlParam  tulpa_section_template required The ID of tulpa_section_template. Example: 1
   */
  public function show(Request $request, $id = null)
  {
    return ModelHelper::ws_ShowHandler($this, $request, $id);
  }

  /**
   * Update
   *
   * @urlParam  tulpa_section_template required The ID of tulpa_section_template. Example: 1
   * @bodyParam name string Example: Tulpa Section Template
   * @bodyParam component_name string Example: ComponantName
   * @bodyParam remark string Example: Remark
   * @bodyParam fields object No-example
   */
  public function update(Request $request, $id)
  {
    return ModelHelper::ws_UpdateHandler($this, $request, $id);
  }

  /**
   * Delete
   *
   * @urlParam  tulpa_section_template required The ID of tulpa_section_template. Example: 2
   */
  public function destroy($id)
  {
    return ModelHelper::ws_DestroyHandler($this, $id);
  }
}
