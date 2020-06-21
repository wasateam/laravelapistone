<?php

namespace Wasateam\Laravelapistone\Controllers;

use App\Http\Controllers\Controller;
use Wasateam\Laravelapistone\Helpers\ModelHelper;
use Illuminate\Http\Request;
use Validator;

class ModelController extends Controller
{
  /**
   * Get Model List
   */
  public function index(Request $request, $id = null)
  {
    // Setting
    $setting = ModelHelper::getSetting($this);

    // Snap
    $snap = ModelHelper::indexGetSnap($setting, $request, $id);

    // Collection
    try {
      $collection = ModelHelper::indexGetPaginate($snap, $request);
    } catch (\Throwable $th) {
      return response()->json([
        'message' => 'get index error.',
      ]);
    }

    // Return Result
    return ModelHelper::indexGetResourceCollection($collection, $setting);
  }

  public function store(Request $request, $id = null)
  {
    // Setting
    $setting = ModelHelper::getSetting($this);

    // Validation
    $rules     = ModelHelper::getValidatorRules($setting, 'store');
    $validator = Validator::make($request->all(), $rules);
    if ($validator->fails()) {
      return response()->json([
        'message' => $validator->messages(),
      ], 400);
    }

    // New Model
    $model = new $setting->model;

    // Default Value
    $model = ModelHelper::storeDefaultValueSet($model, $setting);

    // Input Value
    $model = ModelHelper::setInputFields($model, $setting, $request);

    // User Updated Record
    $model = ModelHelper::setUserRecord($model, $setting);

    // Parent
    try {
      $model = ModelHelper::storeParentId($model, $setting, $id);
    } catch (\Throwable $th) {
      return response()->json([
        'message' => 'store parent id fail.',
      ], 400);
    }

    // Save
    try {
      $model->save();
    } catch (\Throwable $th) {
      return response()->json([
        'message' => 'data store fail.',
      ], 400);
    }

    // Belongs To Value
    $model = ModelHelper::setBelongsTo($model, $setting, $request);

    // Belongs To Many Value
    $model = ModelHelper::setBelongsToMany($model, $setting, $request);

    // Locale Set
    try {
      ModelHelper::setLocale($model, $setting, $request);
    } catch (\Throwable $th) {
      return response()->json([
        'message' => 'locales data store fail.',
      ], 400);
    }

    return new $setting->resource($model);
  }

  public function show(Request $request, $id)
  {
    // Setting
    $setting = ModelHelper::getSetting($this);

    // Get
    try {
      $model = $setting->model::where('id', $id)->where($setting->custom_get_conditions)->first();
    } catch (\Throwable $th) {
      return response()->json([
        'message' => 'no data.',
      ], 400);
    }

    if (!$model) {
      return response()->json([
        'message' => 'no data.',
      ], 400);
    }

    return new $setting->resource($model);
  }

  public function update(Request $request, $id)
  {
    // Setting
    $setting = ModelHelper::getSetting($this);

    // Validation
    $rules     = ModelHelper::getValidatorRules($setting, 'update');
    $validator = Validator::make($request->all(), $rules);
    if ($validator->fails()) {
      return response()->json([
        'message' => $validator->messages(),
      ], 400);
    }

    // Find Model
    $model = $setting->model::find($id);
    if (!$model) {
      return response()->json([
        'message' => 'no data.',
      ], 400);
    }

    // Input Value
    $model = ModelHelper::setInputFields($model, $setting, $request);

    // User Updated Record
    $model = ModelHelper::setUserRecord($model, $setting);

    // Save
    try {
      $model->save();
    } catch (\Throwable $th) {
      return response()->json([
        'message' => 'data store fail.',
      ], 400);
    }

    // Belongs To Value
    $model = ModelHelper::setBelongsTo($model, $setting, $request);

    // Belongs To Many Value
    $model = ModelHelper::setBelongsToMany($model, $setting, $request);

    // Locale Set
    try {
      ModelHelper::setLocale($model, $setting, $request);
    } catch (\Throwable $th) {
      return response()->json([
        'message' => 'locales data store fail.',
      ], 400);
    }

    return new $setting->resource($model);
  }

  public function destroy($id)
  {
    // Setting
    $setting = ModelHelper::getSetting($this);

    // Find Model
    $model = $setting->model::find($id);
    if (!$model) {
      return response()->json([
        'message' => 'no data.',
      ], 400);
    }

    // Delete
    try {
      $destroy = $model->delete();
      if ($destroy) {
        return response()->json([
          "message" => 'data deleted.',
          "status"  => $destroy,
        ], 200);
      } else {
        return response()->json([
          "message" => 'delete error.',
        ], 400);
      }
    } catch (\Throwable $th) {
      return response()->json([
        "message" => 'delete error.',
      ], 400);
    }
  }
}
