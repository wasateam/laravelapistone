<?php

namespace Wasateam\Laravelapistone\Helpers;

use App;
use Auth;
use Exception;
use Illuminate\Http\Request;
use Storage;
use Validator;
use Wasateam\Laravelapistone\Helpers\StorageHelper;

class ModelHelper
{
  public static function ws_IndexHandler($controller, $request, $id = null)
  {
    // Setting
    $setting = self::getSetting($controller);

    // Snap
    $snap = self::indexGetSnap($setting, $request, $id);

    // Collection
    $collection = self::indexGetPaginate($setting, $snap, $request);
    try {
    } catch (\Throwable $th) {
      return response()->json([
        'message' => 'get index error.',
      ]);
    }

    // Return Result
    return self::indexGetResourceCollection($collection, $setting);
  }

  public static function ws_StoreHandler($controller, $request, $id = null)
  {
    // Setting
    $setting = self::getSetting($controller);

    // Validation
    $rules     = self::getValidatorRules($setting, 'store');
    $validator = Validator::make($request->all(), $rules);
    if ($validator->fails()) {
      return response()->json([
        'message' => $validator->messages(),
      ], 400);
    }

    // New Model
    $model = new $setting->model;

    // Default Value
    $model = self::storeDefaultValueSet($model, $setting);

    // Input Value
    $model = self::setInputFields($model, $setting, $request);

    // User Updated Record
    $model = self::setUserRecord($model, $setting);

    // Parent
    try {
      $model = self::storeParentId($model, $setting, $id);
    } catch (\Throwable $th) {
      return response()->json([
        'message' => 'store parent id fail.',
      ], 400);
    }

    // Belongs To Value
    $model = self::setBelongsTo($model, $setting, $request);

    // Save
    $model->save();
    try {
    } catch (\Throwable $th) {
      return response()->json([
        'message' => 'data store fail.',
      ], 400);
    }

    // Belongs To Many Value
    $model = self::setBelongsToMany($model, $setting, $request);

    // Locale Set
    try {
      self::setLocale($model, $setting, $request);
    } catch (\Throwable $th) {
      return response()->json([
        'message' => 'locales data store fail.',
      ], 400);
    }

    if (count($setting->child_models)) {
      foreach ($setting->child_models as $child_model_key => $child_model) {
        if ($request->filled($child_model_key) && is_array($request->$child_model_key)) {
          foreach ($request->$child_model_key as $child_model_request) {
            $child_model_request = new Request($child_model_request);
            ModelHelper::ws_StoreHandler(new $child_model, $child_model_request, $model->id);
          }
        }
      }
    }

    return new $setting->resource($model);
  }

  public static function ws_BatchStoreHandler($controller, $request, $id = null)
  {
    try {
      foreach ($request->datas as $request_data) {
        $request_data = new Request($request_data);
        ModelHelper::ws_StoreHandler($controller, $request_data, $id);
      }
      return response()->json([
        'message' => 'batch store complete.',
      ], 200);
    } catch (\Throwable $th) {
      return response()->json([
        'message' => 'batch store fail. but some have been created.',
      ], 400);
    }
  }

  public static function ws_ShowHandler($controller, $request, $id = null)
  {
    // Setting
    $setting = self::getSetting($controller);

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

  public static function ws_UpdateHandler($controller, $request, $id)
  {
    // Setting
    $setting = self::getSetting($controller);

    // Validation
    $rules     = self::getValidatorRules($setting, 'update');
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
    $model = self::setInputFields($model, $setting, $request);

    // User Updated Record
    $model = self::setUserRecord($model, $setting);

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

  public static function ws_DestroyHandler($controller, $id)
  {
    // Setting
    $setting = self::getSetting($controller);

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

  // public static function ws_ServiceFileUploadHandler($controller, $request, $filename)
  // {
  //   // Setting
  //   $setting      = self::getSetting($controller);
  //   $content      = $request->getContent();
  //   $disk         = Storage::disk('gcs');
  //   $repo         = StorageHelper::getRandomPath();
  //   $store_value = "@service/{$setting->name}/{$repo}/{$filename}";
  //   try {
  //     $disk->put($store_value, $content);
  //   } catch (\Throwable $th) {
  //     return response()->json([
  //       'message' => 'store file dail.',
  //     ], 400);
  //   }
  //   return response()->json([
  //     'signed_url' => StorageHelper::get_signed_url($repo, $filename, $setting->name),
  //   ]);
  // }

  public static function ws_Upload($controller, $request, $filename, $type, $signed_type = 'general', $id = null, $parent = null, $parent_id = null)
  {
    $setting = self::getSetting($controller);
    $content = $request->getContent();
    $disk    = Storage::disk('gcs');
    $repo    = StorageHelper::getRandomPath();
    if ($signed_type == 'idmatch') {
      $store_value = "{$setting->name}/{$id}/{$type}/{$repo}/{$filename}";
    }
    // if ($signed_type == 'general') {
    //   $store_value = "{$setting->name}/$type}/{$repo}/{$filename}";
    // }else if($signd_type)
    //   if ($parent && $parent_id) {
    //     $store_value = "{$parent}/{$parent_id}/{$store_value}";
    //   }
    try {
      $disk->put($store_value, $content);
    } catch (\Throwable $th) {
      return response()->json([
        'message' => 'store file dail.',
      ], 400);
    }
    return response()->json([
      'signed_url' => StorageHelper::getSignedUrlByStoreValue($store_value, $signed_type),
    ]);
  }

  public static function getSetting($controller)
  {
    $setting                             = collect();
    $setting->model                      = $controller->model;
    $setting->name                       = $controller->name;
    $setting->resource                   = $controller->resource;
    $setting->paginate                   = isset($controller->paginate) ? $controller->paginate : 15;
    $setting->resource_for_collection    = isset($controller->resource_for_collection) ? $controller->resource_for_collection : null;
    $setting->child_models               = isset($controller->child_models) ? $controller->child_models : [];
    $setting->belongs_to                 = isset($controller->belongs_to) ? $controller->belongs_to : [];
    $setting->has_many                   = isset($controller->has_many) ? $controller->has_many : [];
    $setting->belongs_to_many            = isset($controller->belongs_to_many) ? $controller->belongs_to_many : [];
    $setting->filter_belongs_to          = isset($controller->filter_belongs_to) ? $controller->filter_belongs_to : [];
    $setting->filter_has_many            = isset($controller->filter_has_many) ? $controller->filter_has_many : [];
    $setting->filter_belongs_to_many     = isset($controller->filter_belongs_to_many) ? $controller->filter_belongs_to_many : [];
    $setting->search_fields              = isset($controller->search_fields) ? $controller->search_fields : [];
    $setting->search_relationship_fields = isset($controller->search_relationship_fields) ? $controller->search_relationship_fields : [];
    $setting->validation_rules           = isset($controller->validation_rules) ? $controller->validation_rules : [];
    $setting->store_validation_rules     = isset($controller->store_validation_rules) ? $controller->store_validation_rules : null;
    $setting->update_validation_rules    = isset($controller->update_validation_rules) ? $controller->update_validation_rules : null;
    $setting->default_values             = isset($controller->default_values) ? $controller->default_values : [];
    $setting->input_fields               = isset($controller->input_fields) ? $controller->input_fields : [];
    $setting->locale_fields              = isset($controller->locale_fields) ? $controller->locale_fields : [];
    $setting->user_record_field          = isset($controller->user_record_field) ? $controller->user_record_field : null;
    $setting->parent_name                = isset($controller->parent_name) ? $controller->parent_name : null;
    $setting->parent_model               = isset($controller->parent_model) ? $controller->parent_model : null;
    $setting->parent_id_field            = isset($controller->parent_id_field) ? $controller->parent_id_field : null;
    $setting->custom_get_conditions      = isset($controller->custom_get_conditions) ? $controller->custom_get_conditions : [];
    return $setting;
  }

  public static function getValidatorRules($setting, $type = '')
  {
    if (isset($setting->{"{$type}_validation_rules"})) {
      return $setting->{"{$type}_validation_rules"};
    } else {
      return $setting->validation_rules;
    }
  }

  public static function indexGetSnap($setting, $request, $parent_id)
  {
    // Variable
    $order_by  = ($request != null) && $request->filled('order_by') ? $request->order_by : 'id';
    $order_way = ($request != null) && $request->filled('order_way') ? $request->order_way : 'asc';
    $search    = ($request != null) && $request->filled('search') ? $request->search : null;

    // Snap
    $snap = $setting->model::with($setting->belongs_to)->with($setting->has_many)->with($setting->belongs_to_many)->orderBy($order_by, $order_way)->where($setting->custom_get_conditions);
    // $snap = $setting->model::with($setting->belongs_to)->with($setting->has_many)->with($setting->belongs_to_many)->orderByRaw("-{$oder_by} {$order_way}")->where($setting->custom_get_conditions);

    // Filter
    if (($request != null) && count($setting->filter_belongs_to)) {
      foreach ($setting->filter_belongs_to as $filter_belongs_to_item) {
        if ($request->filled($filter_belongs_to_item)) {
          $item_arr = array_map('intval', explode(',', $request->{$filter_belongs_to_item}));
          $snap     = $snap->orWhereIn("{$filter_belongs_to_item}_id", $item_arr);
        }
      }
    }
    if (($request != null) && count($setting->filter_belongs_to_many)) {
      foreach ($setting->filter_belongs_to_many as $filter_belongs_to_many_item) {
        if ($request->filled($filter_belongs_to_many_item)) {
          $item_arr = array_map('intval', explode(',', $request->{$filter_belongs_to_many_item}));
          $snap     = $snap->with($filter_belongs_to_many_item)->orWhereHas($filter_belongs_to_many_item, function ($query) use ($item_arr) {
            foreach ($item_arr as $item_key => $item) {
              if ($item_key == 0) {
                $query = $query->orWhereIn('id', $item_arr);
              } else {
                $query = $query->orWhereIn('id', $item_arr);
              }
            }

          });
        }
      }
    }
    if (($request != null) && count($setting->filter_has_many)) {
      foreach ($setting->filter_has_many as $filter_has_many_item) {
        if ($request->filled($filter_has_many_item)) {
          $item_arr = array_map('intval', explode(',', $request->{$filter_has_many_item}));
          $snap     = $snap->with($filter_has_many_item)->orWhereHas($filter_has_many_item, function ($query) use ($item_arr) {
            foreach ($item_arr as $item_key => $item) {
              if ($item_key == 0) {
                $query = $query->WhereIn('id', $item_arr);
              } else {
                $query = $query->orWhereIn('id', $item_arr);
              }
            }

          });
        }
      }
    }

    // Search
    if ($search && count($setting->search_fields)) {
      $key_count = 0;
      foreach ($setting->search_fields as $search_field_key => $search_field_item) {
        if ($key_count == 0) {
          $snap = $snap->where($search_field_item, 'LIKE', "%{$search}%");
          $key_count++;
        } else {
          $snap = $snap->orWhere($search_field_item, 'LIKE', "%{$search}%");
          $key_count++;
        }
      }
    }
    if ($search && count($setting->search_relationship_fields)) {
      foreach ($setting->search_relationship_fields as $search_field_key => $search_field_value) {
        $search_querys = [];
        foreach ($search_field_value as $search_field_item) {
          $search_querys[] = [$search_field_item, 'LIKE', "%{$search}%"];
        }
        $snap = $snap->with($search_field_key)->orWhereHas($search_field_key, function ($query) use ($search_querys) {
          foreach ($search_querys as $search_query_key => $search_query) {
            if ($search_query_key == 0) {
              $query->where([$search_query]);
            } else {
              $query->orWhere([$search_query]);
            }
          }
        });
      }
    }

    // Parent
    if ($setting->parent_id_field && $parent_id) {
      $snap = $snap->where($setting->parent_id_field, $parent_id);
    }
    return $snap;
  }

  public static function indexGetPaginate($setting, $snap, $request)
  {
    if ($setting->paginate) {
      $page = ($request != null) && $request->filled('page') ? $request->page : 1;
      return $snap->paginate(15, ['*'], 'page', $page);
    } else {
      return $snap->get();
    }
  }

  public static function storeDefaultValueSet($model, $setting)
  {
    foreach ($setting->default_values as $default_value_key => $default_value) {
      $model[$default_value_key] = $default_value;
    }
    return $model;
  }

  public static function setBelongsTo($model, $setting, $request)
  {
    foreach ($setting->belongs_to as $key) {
      if (!$request->has($key)) {
        continue;
      }
      $test_model = $model;
      $test_model->{$key}()->associate($request->{$key});
      if ($test_model->{$key}) {
        $model = $test_model;
      }
    }
    return $model;
  }

  public static function setBelongsToMany($model, $setting, $request)
  {
    foreach ($setting->belongs_to_many as $key) {
      if (!$request->has($key)) {
        continue;
      }
      if (is_string($request->{$key})) {
        $to_json = json_decode($request->{$key});
        if (json_last_error() === 0) {
          $model->{$key}()->sync($to_json);
        }
      } else {
        $model->{$key}()->sync($request->{$key});
      }
    }
    return $model;
  }

  public static function setInputFields($model, $setting, $request)
  {
    foreach ($setting->input_fields as $key) {
      if (!$request->has($key)) {
        continue;
      }
      $model[$key] = $request->{$key};
    }
    return $model;
  }

  public static function setUserRecord($model, $setting)
  {
    if (!$setting->user_record_field) {
      return $model;
    }
    $user                                 = Auth::user();
    $model->{$setting->user_record_field} = $user->id;
    return $model;
  }

  public static function setLocale($model, $setting, $request)
  {
    if (!$request->filled('locales') || !count($setting->locale_fields)) {
      return;
    }
    $locale_model = $setting->model . "Locale";
    $locales      = \App\Locale::get();
    foreach ($locales as $locale) {
      if (isset($request->locales[$locale->code])) {
        $model_locale = $locale_model::where('locale_id', $locale->id)->where("{$setting->name}_id", $model->id)->first();
        if (!$model_locale) {
          $model_locale                           = new $locale_model;
          $model_locale->locale_id                = $locale->id;
          $model_locale->{$setting->name . '_id'} = $model->id;
        }
        $has_value = 0;
        foreach ($setting->locale_fields as $locale_field) {
          if (isset($request->locales[$locale->code][$locale_field])) {
            $model_locale->{$locale_field} = $request->locales[$locale->code][$locale_field];
            $has_value                     = 1;
          }
        }
        if ($has_value) {
          $model_locale = self::setUserRecord($model_locale, $setting);
          try {
            //code...
            $model_locale->save();
          } catch (\Throwable $th) {
            throw $th;
          }
        }
      }
    }
  }

  public static function formatLocales($data_locales)
  {
    $locales       = self::getLocaleList();
    $_data_locales = [];
    foreach ($data_locales as $key => $data_locale) {
      $tar_locale                       = $locales->firstWhere('id', $data_locale['locale_id']);
      $_data_locales[$tar_locale->code] = $data_locale;
    }
    if (empty($_data_locales)) {
      return null;
    } else {
      return $_data_locales;
    }
  }

  public static function getLocaleList()
  {
    return \App\Locale::all();
  }

  public static function storeParentId($model, $setting, $parent_id)
  {
    if ($setting->parent_id_field && $setting->parent_model) {

      // Check Parent exist
      try {
        $parent_model = $setting->parent_model::find($parent_id);
      } catch (\Throwable $th) {
        throw $th;
      }
      if (!$parent_model) {
        throw new Exception('no parent');
      }

      // Save field
      $model->{$setting->parent_id_field} = $parent_id;
    }
    return $model;
  }

  public static function indexGetResourceCollection($collection, $setting)
  {
    if ($setting->resource_for_collection) {
      return $setting->resource_for_collection::collection($collection);
    } else {
      return $setting->resource::collection($collection);
    }
  }

  public static function getMainLocale($data_locales, $attribute_name)
  {
    $main_locale_code = App::getLocale();
    $main_locale      = App\Locale::where('code', $main_locale_code)->first();
    if (!$main_locale) {
      return '';
    }
    $locale_modeldatas = self::getLocaleList();
    $tar_data_locale   = self::getTarDataLocaleByLocaleId($data_locales, $main_locale->id);
    if ($tar_data_locale && isset($tar_data_locale->{$attribute_name})) {
      return $tar_data_locale->{$attribute_name};
    } else {
      foreach ($locale_modeldatas as $locale_modeldata) {
        $tar_data_locale = self::getTarDataLocaleByLocaleId($data_locales, $locale_modeldata->id);
        if ($tar_data_locale && isset($tar_data_locale->{$attribute_name})) {
          return $tar_data_locale->{$attribute_name};
        }
      }
      return '';
    }
  }

  public static function getTarDataLocaleByLocaleId($data_locales, $locale_id)
  {
    $tar_data_locale = $data_locales->filter(function ($item) use ($locale_id) {
      return $item->locale_id == $locale_id;
    })->first();
    return $tar_data_locale;
  }

  public static function getLocalesByDataLocales($data_locales)
  {
    $locales       = self::getLocaleList();
    $_data_locales = [];
    foreach ($data_locales as $key => $data_locale) {
      $tar_locale                       = $locales->firstWhere('id', $data_locale['locale_id']);
      $_data_locales[$tar_locale->code] = $data_locale;
    }
    if (empty($_data_locales)) {
      return null;
    } else {
      return $_data_locales;
    }
  }
}
