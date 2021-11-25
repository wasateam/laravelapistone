<?php

namespace Wasateam\Laravelapistone\Controllers;

use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Http\Request;
use Wasateam\Laravelapistone\Helpers\GcsHelper;
use Wasateam\Laravelapistone\Helpers\ModelHelper;
use Wasateam\Laravelapistone\Helpers\S3Helper;
use Wasateam\Laravelapistone\Models\TulpaPage;

/**
 * @group TulpaPage
 *
 * @authenticated
 *
 * APIs for tulpa_page
 */
class TulpaPageController extends Controller
{
  public $model                   = 'Wasateam\Laravelapistone\Models\TulpaPage';
  public $name                    = 'tulpa_page';
  public $resource                = 'Wasateam\Laravelapistone\Resources\TulpaPage';
  public $resource_for_collection = 'Wasateam\Laravelapistone\Resources\TulpaPageCollection';
  public $input_fields            = [
    'name',
    'route',
    'title',
    'description',
    'og_image',
    'is_active',
    'tags',
    'remark',
    'content',
    'canonical_url',
  ];
  public $filter_fields = [];
  public $search_fields = [
    'name',
    'title',
    'tags',
    'route',
  ];
  public $validation_rules = [
    'route' => 'unique:tulpa_pages',
  ];
  public $belongs_to = [
    'tulpa_page_template',
  ];
  public $belongs_to_many = [];
  public $order_fields    = [
    'updated_at',
    'created_at',
    'route',
  ];
  public $scope_filter_belongs_to_many = [];
  public $user_record_field            = 'updated_admin_id';
  public $user_create_field            = 'created_admin_id';
  public $admin_group                  = true;

  public function __construct()
  {
    if (config('stone.admin_group')) {
      if (config('stone.admin_blur')) {
        $this->belongs_to_many[]                            = 'tulpa_sections';
        $this->belongs_to_many[]                            = 'cmser_groups';
        $this->belongs_to_many[]                            = 'tulpa_cross_items';
        $this->scope_filter_belongs_to_many['cmser_groups'] = [
          'boss',
        ];
      } else {
        $this->belongs_to_many[]                            = 'tulpa_sections';
        $this->belongs_to_many[]                            = 'admin_groups';
        $this->belongs_to_many[]                            = 'tulpa_cross_items';
        $this->scope_filter_belongs_to_many['admin_groups'] = [
          'boss',
        ];
      }
    }
    if (config('stone.country_code')) {
      $this->input_fields[] = 'country_code';
      $this->filter_fields[] = 'country_code';
    }
  }

  /**
   * Index
   * @queryParam search string No-example
   *
   */
  public function index(Request $request, $id = null)
  {
    if (config('stone.mode') == 'cms') {
      return ModelHelper::ws_IndexHandler($this, $request, $id);
    } else if (config('stone.mode') == 'webapi') {
      return ModelHelper::ws_IndexHandler($this, $request, $id, true, function ($snap) {
        $snap = $snap->where('is_active', 1);
        return $snap;
      });
    }
  }

  /**
   * Store
   *
   * @bodyParam name string Example: My Page 1
   * @bodyParam route string Example: page1
   * @bodyParam title string Example: Title for SEO
   * @bodyParam description string Example: Description for SEO
   * @bodyParam og_image url No-example
   * @bodyParam is_active boolean Example: 1
   * @bodyParam tags object Example: ["tag 1","tag 2"]
   * @bodyParam remark string Example: Remark for admins
   * @bodyParam content object No-example
   * @bodyParam canonical_url string Example: https://wasateam.com
   * @bodyParam tulpa_page_template id Example: 1
   * @bodyParam tulpa_sections object No-example
   * @bodyParam admin_groups object No-example
   * @bodyParam tulpa_cross_items object No-example
   * @bodyParam country_code string No-example
   */
  public function store(Request $request, $id = null)
  {
    return ModelHelper::ws_StoreHandler($this, $request, $id);
  }

  /**
   * Show
   *
   * @urlParam  tulpa_page required The ID of tulpa_page. Example: 1
   * @queryParam route_name string No-Example
   */
  public function show(Request $request, $id = null)
  {
    if (config('stone.mode') == 'cms') {
      return ModelHelper::ws_ShowHandler($this, $request, $id);
    } else if (config('stone.mode') == 'webapi') {
      $tulpa_page = TulpaPage::where('route', $request->route_name)->where('is_active', 1)->first();
      return response()->json([
        'data' => new \Wasateam\Laravelapistone\Resources\TulpaPage($tulpa_page),
      ], 200);
    };
  }

  /**
   * Update
   *
   * @urlParam  tulpa_page required The ID of tulpa_page. Example: 1
   * @bodyParam name string Example: My Page 1
   * @bodyParam route string Example: page1
   * @bodyParam title string Example: Title for SEO
   * @bodyParam description string Example: Description for SEO
   * @bodyParam og_image url No-example
   * @bodyParam is_active boolean Example: 1
   * @bodyParam tags object Example: ["tag 1","tag 2"]
   * @bodyParam remark string Example: Remark for admins
   * @bodyParam content object No-example
   * @bodyParam canonical_url string Example: https://wasateam.com
   * @bodyParam tulpa_page_template id Example: 1
   * @bodyParam tulpa_sections object No-example
   * @bodyParam admin_groups object No-example
   * @bodyParam tulpa_cross_items object No-example
   * @bodyParam country_code string No-example
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

  /**
   * Get Upload Url
   * @queryParam name string Example: wasa.png
   *
   */
  public function image_get_upload_url(Request $request)
  {
    $name            = $request->name;
    $storage_service = config('stone.storage.service');
    if ($storage_service == 'gcs') {
      return GcsHelper::getUploadSignedUrlByNameAndPath($name, 'tulpa', '*');
    } else if ($storage_service == 's3') {
      return S3Helper::getUploadSignedUrlByNameAndPath($name, 'tulpa', '*', true);
    }
  }
}
