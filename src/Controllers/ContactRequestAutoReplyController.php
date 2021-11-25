<?php

namespace Wasateam\Laravelapistone\Controllers;

use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Http\Request;
use Wasateam\Laravelapistone\Models\GeneralContent;

/**
 * @group ContactRequestAuthReply
 *
 * @authenticated
 *
 * APIs for contact_request_auth_reply
 */
class ContactRequestAutoReplyController extends Controller
{
  /**
   * Show
   *
   */
  public function show(Request $request)
  {
    // $model = GeneralContent::where('name', 'privacy')->first();

    $snap = GeneralContent::where('name', 'contact-request-auto-reply');
    if ($request->has('country_code')) {
      $snap = $snap->where('country_code', $request->country_code);
    }
    $model = $snap->first();
    if (!$model) {
      $model       = new GeneralContent;
      $model->name = 'contact-request-auto-reply';
      if ($request->has('country_code')) {
        $model->country_code = $request->country_code;
      }
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
    $snap = GeneralContent::where('name', 'contact-request-auto-reply');
    if ($request->has('country_code')) {
      $snap = $snap->where('country_code', $request->country_code);
    }
    $model = $snap->first();
    if (!$model) {
      $model       = new GeneralContent;
      $model->name = 'contact-request-auto-reply';
      if ($request->has('country_code')) {
        $model->country_code = $request->country_code;
      }
      $model->save();
    }
    $model->content = $request->content;
    $model->save();
    return response()->json([
      'content' => $model->content,
    ], 200);
  }
}
