<?php

namespace Wasateam\Laravelapistone\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Wasateam\Laravelapistone\Helpers\ModelHelper;
use Wasateam\Laravelapistone\Models\TulpaPageTemplate;

/**
 * @group TulpaPageTemplate
 *
 * @authenticated
 *
 * APIs for tulpa_page_template
 */
class TulpaPageTemplateController extends Controller
{
  public $model                   = 'Wasateam\Laravelapistone\Models\TulpaPageTemplate';
  public $name                    = 'tulpa_page_template';
  public $resource                = 'Wasateam\Laravelapistone\Resources\TulpaPageTemplate';
  public $resource_for_collection = 'Wasateam\Laravelapistone\Resources\TulpaPageTemplateCollection';
  public $input_fields            = [
    'name',
    'tags',
    'remark',
    'content',
  ];
  public $search_fields = [
    'name',
    'tags',
  ];
  public $belongs_to_many = [
    'tulpa_sections',
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
   * @bodyParam name string Example: My Page 1
   * @bodyParam tags object Example: ["tag 1","tag 2"]
   * @bodyParam remark string Example: Remark for admins
   * @bodyParam content object No-example
   * @bodyParam tulpa_sections object No-example
   */
  public function store(Request $request, $id = null)
  {
    return ModelHelper::ws_StoreHandler($this, $request, $id);
  }

  /**
   * Show
   *
   * @urlParam  tulpa_page_template required The ID of tulpa_page_template. Example: 1
   */
  public function show(Request $request, $id = null)
  {
    return ModelHelper::ws_ShowHandler($this, $request, $id);
  }

  /**
   * Update
   *
   * @urlParam  tulpa_page_template required The ID of tulpa_page_template. Example: 1
   * @bodyParam name string Example: My Page 1
   * @bodyParam tags object Example: ["tag 1","tag 2"]
   * @bodyParam remark string Example: Remark for admins
   * @bodyParam content object No-example
   * @bodyParam tulpa_sections object No-example
   */
  public function update(Request $request, $id)
  {
    return ModelHelper::ws_UpdateHandler($this, $request, $id);
  }

  /**
   * Delete
   *
   * @urlParam  tulpa_page_template required The ID of tulpa_page_template. Example: 2
   */
  public function destroy($id)
  {
    return ModelHelper::ws_DestroyHandler($this, $id);
  }
}
