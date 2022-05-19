<?php

namespace Wasateam\Laravelapistone\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Wasateam\Laravelapistone\Helpers\ModelHelper;

/**
 * @group Showcase 展示項目
 *
 * APIs for show_case
 *
 * sq 排序
 * name 名稱
 * description 描述
 * color 顏色
 * cover_image 封面圖片
 * main_image 內容主要圖片
 * route_name Route名稱
 * tags 標籤
 * is_active 上下架狀態
 * content 內容
 *
 * @authenticated
 */
class ShowcaseController extends Controller
{
  public $model                   = 'Wasateam\Laravelapistone\Models\Showcase';
  public $name                    = 'show_case';
  public $resource                = 'Wasateam\Laravelapistone\Resources\Showcase';
  public $resource_for_collection = 'Wasateam\Laravelapistone\Resources\ShowcaseCollection';
  public $input_fields            = [
    'sq',
    'name',
    'description',
    'color',
    'cover_image',
    'main_image',
    'route_name',
    'tags',
    'is_active',
    'content',
  ];
  public $belongs_to = [
  ];
  public $filter_belongs_to = [
  ];
  public $filter_fields = [
    'is_active',
  ];
  public $order_fields = [
    'sq',
  ];
  public $search_fields = [
    'name',
  ];
  public $order_by         = 'sq';
  public $order_way        = 'desc';
  public $validation_rules = [
    'route_name' => 'unique:showcases',
  ];

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
   * @bodyParam sq string No-example
   * @bodyParam name string No-example
   * @bodyParam description string No-example
   * @bodyParam color string No-example
   * @bodyParam cover_image string No-example
   * @bodyParam main_image string No-example
   * @bodyParam route_name string No-example
   * @bodyParam tags string No-example
   * @bodyParam is_active string No-example
   * @bodyParam content string No-example
   */
  public function store(Request $request, $id = null)
  {
    return ModelHelper::ws_StoreHandler($this, $request, $id);
  }

  /**
   * Show
   *
   * @urlParam  route_name required The route_name of show_case. Example: wasateam
   */
  public function show(Request $request, $id = null)
  {
    if (config('stone.mode') == 'cms') {
      return ModelHelper::ws_ShowHandler($this, $request, $id);
    } else if (config('stone.mode') == 'webapi') {
      $model = $this->model::where('route_name', $id)
        ->where('is_active', 1)
        ->first();
      if (!$model) {
        throw new \Wasateam\Laravelapistone\Exceptions\FindNoDataException('showcase');
      }
      return response()->json([
        'data' => new \Wasateam\Laravelapistone\Resources\TulpaPage($model),
      ], 200);
    };
  }

  /**
   * Update
   *
   * @urlParam  show_case required The ID of show_case. Example: 1
   * @bodyParam sq string No-example
   * @bodyParam name string No-example
   * @bodyParam description string No-example
   * @bodyParam color string No-example
   * @bodyParam cover_image string No-example
   * @bodyParam main_image string No-example
   * @bodyParam route_name string No-example
   * @bodyParam tags string No-example
   * @bodyParam is_active string No-example
   * @bodyParam content string No-example
   *
   */
  public function update(Request $request, $id)
  {
    return ModelHelper::ws_UpdateHandler($this, $request, $id);
  }

  /**
   * Delete
   *
   * @urlParam  show_case required The ID of show_case. Example: 2
   */
  public function destroy($id)
  {
    return ModelHelper::ws_DestroyHandler($this, $id);
  }

  /**
   * Order Get
   *
   */
  public function order_get(Request $request)
  {
    return ModelHelper::ws_OrderGetHandler($this, $request);
  }

  /**
   * Order Patch
   *
   */
  public function order_patch(Request $request)
  {
    return ModelHelper::ws_OrderPatchHandler($this, $request);
  }
}
