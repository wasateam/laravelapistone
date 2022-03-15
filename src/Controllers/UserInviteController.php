<?php

namespace Wasateam\Laravelapistone\Controllers;

use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Http\Request;
use Wasateam\Laravelapistone\Helpers\UserInviteHelper;
use Wasateam\Laravelapistone\Models\User;

/**
 * @group UserInvite 相關動作
 *
 * invite_no 邀請碼
 *
 * @authenticated
 */
class UserInviteController extends Controller
{
  /**
   * Show
   *
   */
  public function show()
  {
    $model = GeneralContent::where('name', 'general_user_invite')->first();
    if (!$model) {
      $model       = new GeneralContent;
      $model->name = 'general_user_invite';
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
    $model = GeneralContent::where('name', 'general_user_invite')->first();
    if (!$model) {
      $model       = new GeneralContent;
      $model->name = 'general_user_invite';
      $model->save();
    }
    $model->content = $request->content;
    $model->save();
    return response()->json([
      'content' => $model->content,
    ], 200);
  }

  /**
   * Check 確認邀請碼可用
   */
  public function check(Request $request)
  {
    if (!$request->has('invite_no')) {
      throw new \Wasateam\Laravelapistone\Exceptions\FieldRequiredException('invite_no');
    }
    $user  = Auth::user();
    $check = UserInviteHelper::check($request->invite_no, $user);
    if (!$check) {
      throw new \Wasateam\Laravelapistone\Exceptions\FindNoDataException('invite_no');
    }
    return response()->json([
      'message' => 'ok',
    ]);
  }
}
