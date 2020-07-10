<?php

namespace Wasateam\Laravelapistone\Controllers;

use App\Http\Controllers\Controller;
use Storage;

class FileController extends Controller
{
  public function show($type, $repo, $name)
  {
    $disk         = Storage::disk('gcs');
    $preurl       = "@service/{$type}/";
    $storage_path = "{$preurl}{$repo}/{$name}";
    try {
      $mimeType = $disk->mimeType($storage_path);
    } catch (\Throwable $th) {
      return response()->json([
        'message' => 'get file type fail or find no file.',
      ], 400);
    }
    try {
      $file = $disk->get($storage_path);
    } catch (\Throwable $th) {
      return response()->json([
        'message' => 'get file type fail or find no file.',
      ], 400);
    }
    return response($file)->header('Content-Type', $mimeType);
  }

  public function show_with_parent($parent, $parent_id, $type, $repo, $name)
  {
    $disk         = Storage::disk('gcs');
    $preurl       = "{$parent}/{$parent_id}/{$type}/";
    $storage_path = "{$preurl}{$repo}/{$name}";
    try {
      $mimeType = $disk->mimeType($storage_path);
    } catch (\Throwable $th) {
      return response()->json([
        'message' => 'get file type fail or find no file.',
      ], 400);
    }
    try {
      $file = $disk->get($storage_path);
    } catch (\Throwable $th) {
      return response()->json([
        'message' => 'get file type fail or find no file.',
      ], 400);
    }
    return response($file)->header('Content-Type', $mimeType);
  }
}
