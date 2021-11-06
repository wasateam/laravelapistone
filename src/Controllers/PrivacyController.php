<?php

namespace Wasateam\Laravelapistone\Controllers;

use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Http\Request;
use Wasateam\Laravelapistone\Models\GeneralContent;

/**
 * @group Privacy
 *
 * @authenticated
 *
 * APIs for privacy
 */
class PrivacyController extends Controller
{
  /**
   * Show
   *
   */
  public function show()
  {
    $model = GeneralContent::where('name', 'privacy')->first();
    if (!$model) {
      $model       = new GeneralContent;
      $model->name = 'privacy';
      $model->save();
    }
    return response()->json([
      'content' => $model->content,
    ], 200);
  }

  /**
   * Update
   *
   * @bodyParam content string Example: My Page 1
   */
  public function update(Request $request)
  {
    if (!$request->has('content')) {
      return response()->json([
        'message' => 'missing fileds.',
      ], 400);
    }
    $model = GeneralContent::where('name', 'privacy')->first();
    if (!$model) {
      $model       = new GeneralContent;
      $model->name = 'privacy';
      $model->save();
    }
    $model->content = $request->content;
    $model->save();
    return response()->json([
      'content' => $model->content,
    ], 200);
  }
}
