<?php

namespace Wasateam\Laravelapistone\Controllers;

use App\Http\Controllers\Controller;
use Storage;

class FileController extends Controller
{
  public function file_idmatch($model, $model_id, $type, $repo, $name)
  {
    $disk         = Storage::disk('gcs');
    $storage_path = "{$model}/{$model_id}/{$type}/{$repo}/{$name}";
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

  public function file_general($model, $type, $repo, $name)
  {
    $disk         = Storage::disk('gcs');
    $storage_path = "{$model}/{$type}/{$repo}/{$name}";
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

  public function file_parentidmatch($parent, $parent_id, $model, $type, $repo, $name)
  {
    $disk         = Storage::disk('gcs');
    $storage_path = "{$parent}/{$parent_id}/{$model}/{$type}/{$repo}/{$name}";
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

  // public function show($type, $model, $repo, $name)
  // {
  //   $disk         = Storage::disk('gcs');
  //   $storage_path = "@{$type}/{$model}/{$repo}/{$name}";
  //   try {
  //     $mimeType = $disk->mimeType($storage_path);
  //   } catch (\Throwable $th) {
  //     return response()->json([
  //       'message' => 'get file type fail or find no file.',
  //     ], 400);
  //   }
  //   try {
  //     $file = $disk->get($storage_path);
  //   } catch (\Throwable $th) {
  //     return response()->json([
  //       'message' => 'get file type fail or find no file.',
  //     ], 400);
  //   }
  //   return response($file)->header('Content-Type', $mimeType);
  // }

  // public function show_with_parent($parent, $parent_id, $type, $model, $repo, $name)
  // {
  //   $disk         = Storage::disk('gcs');
  //   $storage_path = "{$parent}/{$parent_id}/@{$type}/{$model}/{$repo}/{$name}";
  //   try {
  //     $mimeType = $disk->mimeType($storage_path);
  //   } catch (\Throwable $th) {
  //     return response()->json([
  //       'message' => 'get file type fail or find no file.',
  //     ], 400);
  //   }
  //   try {
  //     $file = $disk->get($storage_path);
  //   } catch (\Throwable $th) {
  //     return response()->json([
  //       'message' => 'get file type fail or find no file.',
  //     ], 400);
  //   }
  //   return response($file)->header('Content-Type', $mimeType);
  // }
}
