<?php

namespace Wasateam\Laravelapistone\Controllers;

use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;
use Wasateam\Laravelapistone\Helpers\ModelHelper;

/**
 * @group Notification
 *
 * @authenticated
 *
 * APIs for notification
 */
class NotificationController extends Controller
{
  public $model        = 'Wasateam\Laravelapistone\Models\Notification';
  public $name         = 'notification';
  public $resource     = 'Wasateam\Laravelapistone\Resources\Notification';
  public $input_fields = [
  ];
  public $belongs_to = [
  ];
  public $filter_fields = [
    'type',
    'read_at',
  ];
  public $filter_belongs_to = [
    'user',
    'admin',
  ];
  public $order_fields = [
    'created_at',
    'updated_at',
  ];

  /**
   * Index
   * @queryParam search string No-example
   *
   */
  public function index(Request $request, $id = null)
  {
    return ModelHelper::ws_IndexHandler($this, $request, $id, false, function ($snap) {
      $notificable_type = config('stone.notification.notifiable_type_user');
      $snap             = $snap->where('notifiable_type', $notificable_type);
      return $snap;
    });
  }

  /**
   * Show
   *
   * @urlParam  blog required The ID of blog. Example: 1
   */
  public function show(Request $request, $id = null)
  {
    return ModelHelper::ws_ShowHandler($this, $request, $id);
  }

  /**
   * Delete
   *
   * @urlParam  blog required The ID of blog. Example: 2
   */
  public function destroy($id)
  {
    return ModelHelper::ws_DestroyHandler($this, $id);
  }

  /**
   *  User Index
   *
   */
  public function user_index(Request $request)
  {
    $page  = ($request != null) && $request->filled('page') ? $request->page : 1;
    $user  = Auth::user();
    $notis = $user->notifications->paginate(15, ['*'], 'page', $page);
    return \App\Http\Resources\Notification::collection($notis);
  }

  /**
   * User Index Unread
   *
   */
  public function user_index_unread()
  {
    $page  = ($request != null) && $request->filled('page') ? $request->page : 1;
    $user  = Auth::user();
    $notis = $user->unreadNotifications->paginate(15, ['*'], 'page', $page);
    return \App\Http\Resources\Notification::collection($notis);
  }

  /**
   * User Read
   *
   */
  public function user_read($id)
  {
    $user         = Auth::user();
    $notification = DatabaseNotification::where('notifiable_id', $user->id)->where('id', $id)->first();
    if (!$notification) {
      return response()->json([
        'message' => 'cannot find notification.',
      ], 400);
    }
    $notification->markAsRead();
    return response()->json([
      "message" => 'mark read.',
    ], 200);
  }

  /**
   * User ReadAll
   *
   */
  public function user_readall()
  {
    $user = Auth::user();
    $user->unreadNotifications->markAsRead();
    return response()->json([
      "message" => 'all mark read.',
    ], 200);
  }
}
