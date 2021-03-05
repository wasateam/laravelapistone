<?php

namespace Wasateam\Laravelapistone\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Wasateam\Laravelapistone\Helpers\ModelHelper;

/**
 * @group TulpaPage
 *
 * @authenticated
 *
 * APIs for tulpa_page
 */
class TulpaPageController extends Controller
{
  public $model        = 'Wasateam\Laravelapistone\Models\TulpaPage';
  public $name         = 'tulpa_page';
  public $resource     = 'Wasateam\Laravelapistone\Resources\TulpaPage';
  public $input_fields = [
    'route',
    'title',
    'description',
    'og_image',
    'is_active',
    'tags',
    'remark',
    'status',
    'tulpa_sections',
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
   * @bodyParam route string Example: page1
   * @bodyParam title string Example: Title for SEO
   * @bodyParam description string Example: Description for SEO
   * @bodyParam og_image url No-example
   * @bodyParam is_active boolean Example: 1
   * @bodyParam tags object Example: ["tag1","tag2"]
   * @bodyParam remark string Example: Remark for admins
   * @bodyParam status int No-example
   * @bodyParam tulpa_sections object No-example
   */
  public function store(Request $request, $id = null)
  {
    return ModelHelper::ws_StoreHandler($this, $request, $id);
  }

  /**
   * Show
   *
   * @urlParam  tulpa_page required The ID of tulpa_page. Example: 1
   */
  public function show(Request $request, $id = null)
  {
    return ModelHelper::ws_ShowHandler($this, $request, $id);
  }

  /**
   * Update
   *
   * @urlParam  tulpa_page required The ID of tulpa_page. Example: 1
   * @bodyParam route string Example: page1
   * @bodyParam title string Example: Title for SEO
   * @bodyParam description string Example: Description for SEO
   * @bodyParam og_image url No-example
   * @bodyParam is_active boolean Example: 1
   * @bodyParam tags object Example: ["tag1","tag2"]
   * @bodyParam remark string Example: Remark for admins
   * @bodyParam status int No-example
   * @bodyParam tulpa_sections object No-example
   */
  public function update(Request $request, $id)
  {
    return ModelHelper::ws_UpdateHandler($this, $request, $id);
  }

  /**
   * Delete
   *
   * @urlParam  tulpa_page required The ID of tulpa_page. Example: 2
   */
  public function destroy($id)
  {
    return ModelHelper::ws_DestroyHandler($this, $id);
  }
}
