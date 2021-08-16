<?php

namespace Wasateam\Laravelapistone\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Storage;
use Wasateam\Laravelapistone\Helpers\ModelHelper;
use Wasateam\Laravelapistone\Helpers\StorageHelper;

class WsModelController extends Controller
{
  public function ws_index(Request $request, $id = null)
  {
    return ModelHelper::ws_IndexHandler($this, $request, $id);
  }
  public function ws_store(Request $request, $id = null)
  {
    return ModelHelper::ws_StoreHandler($this, $request, $id);
  }
  public function ws_show(Request $request, $id = null)
  {
    return ModelHelper::ws_ShowHandler($this, $request, $id);
  }
  public function ws_update(Request $request, $id)
  {
    return ModelHelper::ws_UpdateHandler($this, $request, $id);
  }
  public function ws_destroy($id)
  {
    return ModelHelper::ws_DestroyHandler($this, $id);
  }

  public function service_file_upload(Request $request, $filename)
  {
    // Setting
    $setting      = ModelHelper::getSetting($this);
    $content      = $request->getContent();
    $disk         = Storage::disk('gcs');
    $repo         = StorageHelper::getRandomPath();
    $storage_path = "@service/{$setting->name}/{$repo}/{$filename}";
    try {
      $disk->put($storage_path, $content);
    } catch (\Throwable$th) {
      return response()->json([
        'message' => 'store file dail.',
      ], 400);
    }
    return response()->json([
      'signed_url' => StorageHelper::get_signed_url($repo, $filename, $setting->name),
    ]);
  }

  public function file_upload_in_parent(Request $request, $parent_id, $filename)
  {
    // Setting
    $setting = ModelHelper::getSetting($this);
    $content = $request->getContent();
    $disk    = Storage::disk('gcs');
    $repo    = StorageHelper::getRandomPath();
    $parent  = $setting->parent_model::find($parent_id);
    if (!$parent) {
      return response()->json([
        'message' => 'find no data.',
      ], 400);
    }
    $storage_path = "{$setting->parent_name}/{$parent_id}/{$setting->name}/{$repo}/{$filename}";
    error_log($storage_path);
    try {
      $disk->put($storage_path, $content);
    } catch (\Throwable$th) {
      return response()->json([
        'message' => 'store file dail.',
      ], 400);
    }
    return response()->json([
      'signed_url' => StorageHelper::get_signed_url($repo, $filename, $setting->name, $setting->parent_name, $parent_id),
    ]);
  }
}
