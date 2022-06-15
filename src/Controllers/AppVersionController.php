<?php

namespace Wasateam\Laravelapistone\Controllers;

use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Http\Request;
use Wasateam\Laravelapistone\Models\GeneralContent;

/**
 * @group AppVersion
 *
 * @authenticated
 *
 * APIs for app_version
 */
class AppVersionController extends Controller
{
  /**
   * Show App Version Min Android
   *
   */
  public function show_app_version_min_android()
  {
    $model = GeneralContent::where('name', 'app_version_min_android')->first();
    if (!$model) {
      $model       = new GeneralContent;
      $model->name = 'app_version_min_android';
      $model->save();
    }
    return response()->json([
      'content' => $model->content,
    ], 200);
  }

  /**
   * Update App Version Min Android
   *
   * @bodyParam content string Example: My Page 1
   */
  public function update_app_version_min_android(Request $request)
  {
    if (!$request->has('content')) {
      return response()->json([
        'message' => 'missing fileds.',
      ], 400);
    }
    $model = GeneralContent::where('name', 'app_version_min_android')->first();
    if (!$model) {
      $model       = new GeneralContent;
      $model->name = 'app_version_min_android';
      $model->save();
    }
    $model->content = $request->content;
    $model->save();
    return response()->json([
      'content' => $model->content,
    ], 200);
  }

  /**
   * Show App Version Min iOS
   *
   */
  public function show_app_version_min_ios()
  {
    $model = GeneralContent::where('name', 'app_version_min_ios')->first();
    if (!$model) {
      $model       = new GeneralContent;
      $model->name = 'app_version_min_ios';
      $model->save();
    }
    return response()->json([
      'content' => $model->content,
    ], 200);
  }

  /**
   * Update App Version Min iOS
   *
   * @bodyParam content string Example: My Page 1
   */
  public function update_app_version_min_ios(Request $request)
  {
    if (!$request->has('content')) {
      return response()->json([
        'message' => 'missing fileds.',
      ], 400);
    }
    $model = GeneralContent::where('name', 'app_version_min_ios')->first();
    if (!$model) {
      $model       = new GeneralContent;
      $model->name = 'app_version_min_ios';
      $model->save();
    }
    $model->content = $request->content;
    $model->save();
    return response()->json([
      'content' => $model->content,
    ], 200);
  }
}
