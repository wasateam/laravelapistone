<?php

namespace Wasateam\Laravelapistone\Helpers;

use App;
use Auth;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Storage;
use Validator;
use Wasateam\Laravelapistone\Helpers\AuthHelper;
use Wasateam\Laravelapistone\Helpers\StorageHelper;
use Wasateam\Laravelapistone\Models\Locale;

class ModelHelper
{
  public static function ws_IndexHandler($controller, $request, $id = null, $getall = false, $custom_snap_handler = null, $limit = true, $custom_scope_handler = null)
  {
    // Setting
    $setting = self::getSetting($controller);

    // Snap
    $snap = self::indexGetSnap($setting, $request, $id, $limit);

    if ($custom_snap_handler) {
      $snap = $custom_snap_handler($snap);
    }

    // ScopeFilter
    $check = AuthHelper::checkAuthScope($request, $setting->mocu_filters, $custom_scope_handler);

    if (!$check) {
      return response()->json([
        'message' => 'Invalid Scope(s)',
      ], 403);
    }

    // XXX Filter User
    $snap = self::userFilterSnap($snap, $setting);

    // Collection
    $collection = self::indexGetPaginate($setting, $snap, $request, $getall);
    try {
    } catch (\Throwable $th) {
      return response()->json([
        'message' => 'get index error.',
      ]);
    }

    // Return Result
    return self::indexGetResourceCollection($collection, $setting);
  }

  public static function ws_StoreHandler($controller, $request, $id = null, $complete_action = null, $before_save_action = null, $create_with_new_version = false, $version_controller = null, $return_resource = true, $custom_scope_handler = null)
  {
    // Setting
    $setting = self::getSetting($controller);

    // Validation
    $rules     = self::getValidatorRules($setting, 'store');
    $validator = Validator::make($request->all(), $rules, $setting->validation_messages);
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

    // User Create Record
    $model = self::setUserCreate($model, $setting);

    // Scope
    $check = AuthHelper::checkAuthScope($request, $setting->mocu_filters, $custom_scope_handler);

    if (!$check) {
      return response()->json([
        'message' => 'Invalid Scope(s)',
      ], 403);
    }

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

    // Before Save Action
    if ($before_save_action) {
      $model = $before_save_action($model);
    }

    $model = self::setUUID($model, $setting);

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

    // Admin Group
    self::adminGroupStoreHandler($model, $setting);

    // CountryCode
    self::countryCodeStoreHandler($model, $setting);

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

    if ($complete_action) {
      $complete_action($model);
    }

    if ($create_with_new_version && $version_controller) {
      self::ws_StoreHandler(new $version_controller, $request, $model->id, null, null, false, null, false);
    }

    Self::ws_Log($model, $controller, 'create');

    if ($return_resource) {
      return new $setting->resource($model);
    }

  }

  public static function ws_ShowHandler($controller, $request, $id = null, $custom_snap_handler = null, $custom_scope_handler = null)
  {
    // Setting
    $setting = self::getSetting($controller);

    // Snap
    $snap = $setting->model::where('id', $id)->where($setting->custom_get_conditions);
    if ($custom_snap_handler) {
      $snap = $custom_snap_handler($snap);
    }

    // Scope
    $check = AuthHelper::checkAuthScope($request, $setting->mocu_filters, $custom_scope_handler);

    if (!$check) {
      return response()->json([
        'message' => 'Invalid Scope(s)',
      ], 403);
    }

    // Filter User
    $snap = self::userFilterSnap($snap, $setting);

    // Filter Admin Group
    $snap = self::adminGroupFilterSnap($snap, $setting);

    // Filter Country Code
    $snap = self::countryCodeFilterSnap($snap, $setting);

    // Get
    try {
      $model = $snap->first();
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

  public static function ws_UpdateHandler($controller, $request, $id, $rules = [], $complete_action = null, $custom_scope_handler = null)
  {
    // Setting
    $setting = self::getSetting($controller);

    // Validation
    $rules     = self::getValidatorRules($setting, 'update', $id);
    $validator = Validator::make($request->all(), $rules, $setting->validation_messages);
    if ($validator->fails()) {
      return response()->json([
        'message' => $validator->messages(),
      ], 400);
    }

    // Find Model
    $snap = $setting->model::where('id', $id);

    // Scope
    $check = AuthHelper::checkAuthScope($request, $setting->mocu_filters, $custom_scope_handler);

    if (!$check) {
      return response()->json([
        'message' => 'Invalid Scope(s)',
      ], 403);
    }

    // Filter User
    $snap = self::userFilterSnap($snap, $setting);

    // Filter Admin Group
    $snap = self::adminGroupFilterSnap($snap, $setting);

    // Filter Country Code
    $snap = self::countryCodeFilterSnap($snap, $setting);

    $model = $snap->first();
    if (!$model) {
      return response()->json([
        'message' => 'no data.',
      ], 400);
    }

    // Input Value
    $model = self::setInputFields($model, $setting, $request);

    // User Updated Record
    $model = self::setUserRecord($model, $setting);

    // Belongs To Value
    $model = ModelHelper::setBelongsTo($model, $setting, $request);

    // Save
    try {
      $model->save();
    } catch (\Throwable $th) {
      return response()->json([
        'message' => 'data update fail.',
      ], 400);
    }

    // Belongs To Many Value
    $model = ModelHelper::setBelongsToMany($model, $setting, $request);

    // Locale Set
    try {
      ModelHelper::setLocale($model, $setting, $request);
    } catch (\Throwable $th) {
      return response()->json([
        'message' => 'locales data update fail.',
      ], 400);
    }

    if (count($setting->child_models)) {
      foreach ($setting->child_models as $child_model_key => $child_model) {
        if ($request->filled($child_model_key) && is_array($request->$child_model_key)) {
          // Remove Unbind
          foreach ($model->{$child_model_key} as $child_check) {
            $has = 0;
            foreach ($request->$child_model_key as $child_model_request) {
              $child_model_request = new Request($child_model_request);
              if ($child_model_request->id && $child_model_request->id == $child_check->id) {
                $has = 1;
              }
            }
            if (!$has) {
              $child_check->delete();
            }
          }

          foreach ($request->$child_model_key as $child_model_request) {
            $child_model_request = new Request($child_model_request);
            if ($child_model_request->id) {
              ModelHelper::ws_UpdateHandler(new $child_model, $child_model_request, $child_model_request->id);
            } else {
              ModelHelper::ws_StoreHandler(new $child_model, $child_model_request, $model->id);
            }
          }
        }
      }
    }

    if ($complete_action) {
      $complete_action($model);
    }

    Self::ws_Log($model, $controller, 'update');

    return new $setting->resource($model);
  }

  public static function ws_DestroyHandler($controller, $id, $complete_action = null, $request = null, $custom_scope_handler = null)
  {
    // Setting
    $setting = self::getSetting($controller);

    // Find Model
    $snap = $setting->model::where('id', $id);

    // Scope
    $check = AuthHelper::checkAuthScope($request, $setting->mocu_filters, $custom_scope_handler);

    if (!$check) {
      return response()->json([
        'message' => 'Invalid Scope(s)',
      ], 403);
    }

    // Filter User
    $snap = self::userFilterSnap($snap, $setting);

    // Filter Admin Group
    $snap = self::adminGroupFilterSnap($snap, $setting);

    // Filter Country Code
    $snap = self::countryCodeFilterSnap($snap, $setting);

    $model = $snap->first();
    if (!$model) {
      return response()->json([
        'message' => 'no data.',
      ], 400);
    }

    Self::ws_Log($model, $controller, 'delete');

    // Delete
    try {
      $destroy = $model->delete();
      if ($destroy) {
        if ($complete_action) {
          $complete_action($model);
        }
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

  public static function log_save($log_model, $record_model, $user, $user_id_name, $action, $controller, $user_uuid = true, $model_uuid = false)
  {
    if ($user_uuid) {
      $user_id_key = 'uuid';
    } else {
      $user_id_key = 'id';
    }
    if ($model_uuid) {
      $model_id_key = 'uuid';
    } else {
      $model_id_key = 'id';
    }
    $log = new $log_model;
    if ($user) {
      $user_id = $user->{$user_id_key};
    } else if (Auth::user()) {
      $user_id = Auth::user()->{$user_id_key};
    } else {
      $user_id = null;
    }
    $log->{$user_id_name} = $user_id;
    $log->payload         = [
      'user_id'   => $user_id,
      'action'    => $action,
      'target'    => $controller->name,
      'target_id' => $record_model ? $record_model->{$model_id_key} : null,
      'ip'        => \Request::ip(),
    ];
    $log->save();
  }

  public static function ws_Log($model, $controller, $action, $user = null)
  {
    if (!config('stone.log.is_active')) {
      return;
    }

    $log_model = config('stone.log.model');
    $log       = new $log_model;
    $user_id   = null;
    if ($user) {
      $user_id = $user->id;
    } else if (Auth::user()) {
      $user_id = Auth::user()->id;
    }
    $log[config('stone.auth.model_name') . '_id'] = $user_id;
    $log->payload                                 = [
      'userModelName' => $user_id ? config('stone.auth.model_name') : null,
      'user_id'       => $user_id,
      'action'        => $action,
      'target'        => $controller->name,
      'target_id'     => $action === 'signin' || $action === 'signout' ? null : $model->id,
      'ip'            => \Request::ip(),
    ];
    $log->save();

  }

  public static function ws_findByIds($controller, $request)
  {
    $ids = array_map('intval', explode(',', $request->ids));
    return $controller->model::findMany($ids);
  }

  public static function ws_BatchStoreHandler($controller, $request, $id = null)
  {
    $result_data = [];
    try {
      foreach ($request->datas as $request_data) {
        $request_data  = new Request($request_data);
        $result_data[] = ModelHelper::ws_StoreHandler($controller, $request_data, $id);
      }
      return $result_data;
      // return response()->json([
      //   'message' => 'batch store complete.',
      //   'data'    => $result_data,
      // ], 200);
    } catch (\Throwable $th) {
      return response()->json([
        'message' => 'batch store fail. but some have been created.',
      ], 400);
    }
  }

  public static function ws_Upload($controller, $request, $filename, $type, $signed_type = 'general', $id = null, $parent = null, $parent_id = null, $signed_time = 15)
  {
    $setting = self::getSetting($controller);
    $content = $request->getContent();
    $disk    = Storage::disk(config('stone.storage.service'));
    $repo    = StorageHelper::getRandomPath();
    if ($signed_type == 'idmatch') {
      $store_value = "{$setting->name}/{$id}/{$type}/{$repo}/{$filename}";
    } else if ($signed_type == 'general') {
      $store_value = "{$setting->name}/{$type}/{$repo}/{$filename}";
    } else if ($signed_type == 'parentidmatch') {
      $store_value = "{$parent}/{$parent_id}/{$setting->name}/{$type}/{$repo}/{$filename}";
    }
    try {
      $disk->put($store_value, $content);
    } catch (\Throwable $th) {
      return response()->json([
        'message' => 'store file dail.',
      ], 400);
    }
    return response()->json([
      'signed_url' => StorageHelper::getSignedUrlByStoreValue($store_value, $signed_type, $signed_time),
    ]);
  }

  public static function getSetting($controller)
  {
    $setting                                  = collect();
    $setting->model                           = $controller->model;
    $setting->name                            = $controller->name;
    $setting->resource                        = $controller->resource;
    $setting->table_name                      = isset($controller->table_name) ? $controller->table_name : "{$setting->name}s";
    $setting->locale_table_name               = isset($controller->locale_table_name) ? $controller->locale_table_name : "{$setting->name}_locales";
    $setting->version_table_name              = isset($controller->version_table_name) ? $controller->version_table_name : "{$setting->name}_versions";
    $setting->version_locale_table_name       = isset($controller->version_locale_table_name) ? $controller->version_locale_table_name : "{$setting->name}_version_locales";
    $setting->paginate                        = isset($controller->paginate) ? $controller->paginate : 15;
    $setting->getallwhentimefield             = isset($controller->getallwhentimefield) ? $controller->getallwhentimefield : 0;
    $setting->resource_for_collection         = isset($controller->resource_for_collection) ? $controller->resource_for_collection : null;
    $setting->child_models                    = isset($controller->child_models) ? $controller->child_models : [];
    $setting->belongs_to                      = isset($controller->belongs_to) ? $controller->belongs_to : [];
    $setting->has_many                        = isset($controller->has_many) ? $controller->has_many : [];
    $setting->belongs_to_many                 = isset($controller->belongs_to_many) ? $controller->belongs_to_many : [];
    $setting->filter_fields                   = isset($controller->filter_fields) ? $controller->filter_fields : [];
    $setting->filter_fields_in_relationships  = isset($controller->filter_fields_in_relationships) ? $controller->filter_fields_in_relationships : [];
    $setting->filter_belongs_to               = isset($controller->filter_belongs_to) ? $controller->filter_belongs_to : [];
    $setting->filter_has_many                 = isset($controller->filter_has_many) ? $controller->filter_has_many : [];
    $setting->filter_belongs_to_many          = isset($controller->filter_belongs_to_many) ? $controller->filter_belongs_to_many : [];
    $setting->filter_relationship_fields      = isset($controller->filter_relationship_fields) ? $controller->filter_relationship_fields : [];
    $setting->filter_user_fields              = isset($controller->filter_user_fields) ? $controller->filter_user_fields : [];
    $setting->filter_user_disable_scopes      = isset($controller->filter_user_disable_scopes) ? $controller->filter_user_disable_scopes : ['boss'];
    $setting->filter_relationship_user_fields = isset($controller->filter_relationship_user_fields) ? $controller->filter_relationship_user_fields : [];
    $setting->search_fields                   = isset($controller->search_fields) ? $controller->search_fields : [];
    $setting->search_relationship_fields      = isset($controller->search_relationship_fields) ? $controller->search_relationship_fields : [];
    $setting->time_relationship_fields        = isset($controller->time_relationship_fields) ? $controller->time_relationship_fields : [];
    $setting->validation_messages             = isset($controller->validation_messages) ? $controller->validation_messages : [];
    $setting->validation_rules                = isset($controller->validation_rules) ? $controller->validation_rules : [];
    $setting->store_validation_rules          = isset($controller->store_validation_rules) ? $controller->store_validation_rules : null;
    $setting->update_validation_rules         = isset($controller->update_validation_rules) ? $controller->update_validation_rules : null;
    $setting->default_values                  = isset($controller->default_values) ? $controller->default_values : [];
    $setting->input_fields                    = isset($controller->input_fields) ? $controller->input_fields : [];
    $setting->locale_fields                   = isset($controller->locale_fields) ? $controller->locale_fields : [];
    $setting->user_record_field               = isset($controller->user_record_field) ? $controller->user_record_field : null;
    $setting->user_create_field               = isset($controller->user_create_field) ? $controller->user_create_field : null;
    $setting->parent_name                     = isset($controller->parent_name) ? $controller->parent_name : null;
    $setting->parent_model                    = isset($controller->parent_model) ? $controller->parent_model : null;
    $setting->parent_id_field                 = isset($controller->parent_id_field) ? $controller->parent_id_field : null;
    $setting->custom_get_conditions           = isset($controller->custom_get_conditions) ? $controller->custom_get_conditions : [];
    $setting->value_match_fields              = isset($controller->value_match_fields) ? $controller->value_match_fields : [];
    $setting->order_fields                    = isset($controller->order_fields) ? $controller->order_fields : [];
    $setting->order_belongs_to                = isset($controller->order_belongs_to) ? $controller->order_belongs_to : [];
    $setting->order_layers_fields             = isset($controller->order_layers_fields) ? $controller->order_layers_fields : [];
    $setting->scope_filter                    = isset($controller->scope_filter) ? $controller->scope_filter : null;
    $setting->admin_group                     = isset($controller->admin_group) ? $controller->admin_group : false;
    $setting->admin_scope_boss                = isset($controller->admin_scope_boss) ? $controller->admin_scope_boss : 'boss';
    $setting->scope_filter_belongs_to_many    = isset($controller->scope_filter_belongs_to_many) ? $controller->scope_filter_belongs_to_many : [];
    $setting->uuid                            = isset($controller->uuid) ? $controller->uuid : false;
    $setting->uuid_key                        = isset($controller->uuid_key) ? $controller->uuid_key : 'uuid';
    $setting->mocu_filters                    = isset($controller->mocu_filters) ? $controller->mocu_filters : [];
    $setting->order_by                        = isset($controller->order_by) ? $controller->order_by : "id";
    $setting->order_way                       = isset($controller->order_way) ? $controller->order_way : "desc";
    $setting->country_code                    = isset($controller->country_code) ? $controller->country_code : null;
    return $setting;
  }

  public static function getValidatorRules($setting, $type = '', $id = null)
  {
    $rules;
    if (isset($setting->{"{$type}_validation_rules"})) {
      $rules = $setting->{"{$type}_validation_rules"};
    } else {
      $rules = $setting->validation_rules;
    }
    if ($type == 'update') {
      $_rules = [];
      foreach ($rules as $key => $rule) {
        $_rule_strings = [];
        $rule_strings  = explode(" ", $rule);
        foreach ($rule_strings as $rule_string_key => $rule_string) {
          if (strpos($rule_string, 'unique') !== false && $key == 'id' && $id) {
            $_rule_strings[] = $rule_string . ',' . 'id' . ',' . $id;
          } else if (strpos($rule_string, 'unique') !== false) {
            $_rule_strings[] = $rule_string . ',' . $key . ',' . $id;
          } else {
            $_rule_strings[] = $rule_string;
          }
        }
        $_rules[$key] = implode('|', $_rule_strings);
      }
      $rules = $_rules;
    }
    return $rules;
  }

  public static function indexGetSnap($setting, $request, $parent_id, $limit = true)
  {
    // Variable
    $order_by   = ($request != null) && $request->filled('order_by') ? $request->order_by : $setting->order_by;
    $order_way  = ($request != null) && $request->filled('order_way') ? $request->order_way : $setting->order_way;
    $start_time = ($request != null) && $request->filled('start_time') ? Carbon::parse($request->start_time) : null;
    $end_time   = ($request != null) && $request->filled('end_time') ? Carbon::parse($request->end_time) : null;
    $time_field = ($request != null) && $request->filled('time_field') ? $request->time_field : 'created_at';
    $search     = ($request != null) && $request->filled('search') ? str_replace(' ', '', $request->search) : null;
    $excludes   = ($request != null) && $request->filled('excludes') ? $request->excludes : null;

    if (isset($end_time) && $end_time->hour == 0 && $end_time->minute == 0 && $end_time->second == 0) {
      $end_time->setTime(23, 59, 59);
    }

    // Snap
    $snap = $setting->model::with($setting->belongs_to)->with($setting->has_many)->with($setting->belongs_to_many);

    // Order
    if (in_array($order_by, $setting->order_fields)) {
      $snap = $snap->orderByRaw("ISNULL({$order_by}), {$order_by} {$order_way}");
    } else if (in_array($order_by, $setting->order_belongs_to)) {
      $order_by = "{$order_by}_id";
      $snap     = $snap->orderByRaw("ISNULL({$order_by}), {$order_by} {$order_way}");
    } else if (isset($setting->order_layers_fields[$order_by])) {
      $layers = $setting->order_layers_fields[$order_by];
      if ($layers == 'locale') {
        $locale_code = \App::getLocale();
        $locale      = Locale::where('code', $locale_code)->first();
        if (!$locale) {
          return null;
        }
        $snap = $snap
          ->select("{$setting->table_name}.*")
          ->join($setting->locale_table_name, function ($join) use ($locale, $setting, $order_by) {
            $join->on("{$setting->locale_table_name}.{$setting->name}_id", '=', "{$setting->table_name}.id")
              ->where("{$setting->locale_table_name}.locale_id", '=', $locale->id);
          })
          ->orderBy("{$setting->locale_table_name}.{$order_by}", $order_way);
      } else if ($layers == 'version') {
        $snap = $snap
          ->select("{$setting->table_name}.*")
          ->join($setting->version_table_name, function ($join) use ($setting) {
            $join->on("{$setting->version_table_name}.{$setting->name}_id", '=', "{$setting->table_name}.id")
              ->whereNull("{$setting->version_table_name}.deleted_at")
              ->whereRaw("{$setting->version_table_name}.id IN (select MAX(a2.id) from {$setting->version_table_name} as a2 join {$setting->table_name} as u2 on u2.id = a2.{$setting->name}_id group by u2.id)")->select('id');
          })
          ->orderByRaw("ISNULL({$order_by}), {$order_by} {$order_way}");
      } else if ($layers == 'version.locale') {
        $locale_code = \App::getLocale();
        $locale      = Locale::where('code', $locale_code)->first();
        if (!$locale) {
          return null;
        }
        $snap = $snap
          ->select("{$setting->table_name}.*")
          ->join($setting->version_table_name, function ($join) use ($setting) {
            $join->on("{$setting->version_table_name}.{$setting->name}_id", '=', "{$setting->table_name}.id")
              ->whereNull("{$setting->version_table_name}.deleted_at")
              ->whereRaw("{$setting->version_table_name}.id IN (select MAX(a2.id) from {$setting->version_table_name} as a2 join {$setting->table_name} as u2 on u2.id = a2.{$setting->name}_id group by u2.id)")->select('id');
          })
          ->join($setting->version_locale_table_name, function ($join) use ($locale, $setting) {
            $join->on("{$setting->version_locale_table_name}.{$setting->name}_version_id", '=', "{$setting->version_table_name}.id")
              ->where("{$setting->version_locale_table_name}.locale_id", '=', $locale->id);
          })
          ->orderBy("{$setting->version_locale_table_name}.{$order_by}", $order_way);
      }
    }

    // Custom Get Conditions
    $snap = $snap->where($setting->custom_get_conditions);

    // Exclude
    if ($excludes) {
      $exclude_arr = array_map('intval', explode(',', $excludes));
      $snap        = $snap->whereNotIn('id', $exclude_arr);
    }

    // Time
    if ($start_time && $end_time) {
      if ($limit) {
        $totalDuration = $end_time->diffInDays($start_time);
        if ($totalDuration > 366) {
          $end_time = Carbon::parse($start_time)->addDays(366);
        }
      }
    } else if ($end_time) {
      $start_time = Carbon::parse($end_time)->addDays(-366);
    } else if ($start_time) {
      $end_time = Carbon::parse($start_time)->addDays(366);
    }

    if (isset($start_time) && isset($end_time)) {

      if (isset($setting->time_relationship_fields[$time_field])) {
        $snap = $snap->whereHas($setting->time_relationship_fields[$time_field], function ($query) use ($time_field, $start_time, $end_time) {
          $query = $query->where($time_field, '>=', $start_time);
          $query = $query->where($time_field, '<=', $end_time);
        });
      } else {
        $snap = $snap->where($time_field, '>=', $start_time);
        $snap = $snap->where($time_field, '<=', $end_time);

      }
    }

    // Filter
    if ($request != null && count($setting->filter_fields)) {
      foreach ($setting->filter_fields as $filter_field) {
        if ($request->filled($filter_field)) {
          if ($request->{$filter_field} == 'null') {
            $snap = $snap->whereNull($filter_field);
          } else if ($request->{$filter_field} == 'not_null') {
            $snap = $snap->whereNotNull($filter_field);
          } else {
            $item_arr = array_map(null, explode(',', $request->{$filter_field}));
            $snap     = $snap->whereIn($filter_field, $item_arr);
          }
        }
      }
    }
    if ($request != null && count($setting->filter_fields_in_relationships)) {
      foreach ($setting->filter_fields_in_relationships as $filter_fields_in_relationship_key => $filter_fields_in_relationship_value) {
        if ($request->filled($filter_fields_in_relationship_key)) {
          $snap = $snap->whereHas($filter_fields_in_relationship_value[0], function ($query) use ($request, $filter_fields_in_relationship_key) {
            return $query->where($filter_fields_in_relationship_key, $request->{$filter_fields_in_relationship_key});
          });
        }
      }
    }
    if (($request != null) && count($setting->filter_belongs_to)) {
      foreach ($setting->filter_belongs_to as $filter_belongs_to_item) {
        if ($request->filled($filter_belongs_to_item)) {
          $item_arr = array_map('intval', explode(',', $request->{$filter_belongs_to_item}));
          $snap     = $snap->whereHas($filter_belongs_to_item, function ($query) use ($item_arr) {
            return $query->whereIn('id', $item_arr);
          });
        }
      }
    }
    if (($request != null) && count($setting->filter_belongs_to_many)) {
      foreach ($setting->filter_belongs_to_many as $filter_belongs_to_many_item) {
        if ($request->filled($filter_belongs_to_many_item)) {
          $item_arr = array_map('intval', explode(',', $request->{$filter_belongs_to_many_item}));
          $snap     = $snap->with($filter_belongs_to_many_item)->whereHas($filter_belongs_to_many_item, function ($query) use ($item_arr) {
            return $query->whereIn('id', $item_arr);
          });
        }
      }
    }
    if (($request != null) && count($setting->filter_has_many)) {
      foreach ($setting->filter_has_many as $filter_has_many_item) {
        if ($request->filled($filter_has_many_item)) {
          $item_arr = array_map('intval', explode(',', $request->{$filter_has_many_item}));
          $snap     = $snap->with($filter_has_many_item)->whereHas($filter_has_many_item, function ($query) use ($item_arr) {
            return $query->whereIn('id', $item_arr);
          });
        }
      }
    }
    if (($request != null) && count($setting->filter_relationship_fields)) {
      foreach ($setting->filter_relationship_fields as $filter_relationship_field_key => $filter_relationship_field_value) {
        if ($request->filled($filter_relationship_field_key)) {
          $item_arr = array_map('intval', explode(',', $request->{$filter_relationship_field_key}));
          $snap     = $snap->whereHas($filter_relationship_field_value[0], function ($query) use ($filter_relationship_field_value, $item_arr) {
            return $query->whereIn('id', $item_arr);
          });
        }
      }
    }

    // Search
    if ($search) {
      $first_search = 0;
      $snap->where(function ($search_query) use ($setting, $search, $first_search) {

        if (count($setting->search_fields)) {
          foreach ($setting->search_fields as $search_field_key => $search_field_item) {
            if ($first_search == 0) {
              $search_query->where($search_field_item, 'LIKE', "%{$search}%");
              $first_search = 1;
            } else {
              $search_query->orWhere($search_field_item, 'LIKE', "%{$search}%");
            }
          }
        }

        // search_relationship_fields
        if (count($setting->search_relationship_fields)) {
          foreach ($setting->search_relationship_fields as $search_field_key => $search_field_value) {
            $search_querys = [];
            foreach ($search_field_value as $search_field_item) {
              $search_querys[] = [$search_field_item, 'LIKE', "%{$search}%"];
            }
            if ($first_search == 0) {
              $search_query->with($search_field_key)->whereHas($search_field_key, function ($query) use ($search_querys) {
                foreach ($search_querys as $search_query_key => $search_query) {
                  if ($search_query_key == 0) {
                    $query->where([$search_query]);
                  } else {
                    $query->orWhere([$search_query]);
                  }
                }
              });
              $first_search = 1;
            } else {
              $search_query->with($search_field_key)->orWhereHas($search_field_key, function ($query) use ($search_querys) {
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
        }
      });
    }

    // Parent
    if ($setting->parent_id_field && $parent_id) {
      $snap = $snap->where($setting->parent_id_field, $parent_id);
    }

    // Filter Admin Group
    $snap = self::adminGroupFilterSnap($snap, $setting);

    // Filter Country Code
    $snap = self::countryCodeFilterSnap($snap, $setting);

    return $snap;
  }

  public static function indexGetPaginate($setting, $snap, $request, $getall = false)
  {
    if ($getall) {
      return $snap->get();
    } else if ($setting->getallwhentimefield && ($request->filled('start_time') || $request->filled('end_time'))) {
      return $snap->get();
    } else if ($request->filled('offset') || $request->filled('limit')) {
      $offset = $request->filled('offset') ? $request->offset : 1;
      $limit  = $request->filled('limit') ? $request->limit : 50;
      $limit  = $limit > 500 ? 500 : $limit;
      return $snap->offset($offset)->limit($limit)->get();
    } else if ($setting->paginate) {
      $page = ($request != null) && $request->filled('page') ? $request->page : 1;
      return $snap->paginate($setting->paginate, ['*'], 'page', $page);
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
      if (count($setting->scope_filter_belongs_to_many) && array_key_exists($key, $setting->scope_filter_belongs_to_many)) {
        $scope_check = false;
        foreach ($setting->scope_filter_belongs_to_many as $scope_filter_belongs_to_many_key => $scope_filter_belongs_to_many_value) {
          $admin = Auth::user();
          foreach ($admin->scopes as $scope) {
            if (in_array($scope, $scope_filter_belongs_to_many_value)) {
              $scope_check = true;
            }
          }
        }
        if (!$scope_check) {
          continue;
        }
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

  public static function setUserCreate($model, $setting)
  {
    if (!$setting->user_create_field) {
      return $model;
    }
    $user                                 = Auth::user();
    $model->{$setting->user_create_field} = $user->id;
    return $model;
  }

  public static function setLocale($model, $setting, $request)
  {
    if (!$request->filled('locales') || !count($setting->locale_fields)) {
      return;
    }
    $locale_model = $setting->model . "Locale";
    $locales      = Locale::get();
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
    return Locale::all();
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
    $main_locale      = Locale::where('code', $main_locale_code)->first();
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

  public static function userFilterSnap($snap, $setting)
  {
    if (count($setting->filter_user_fields) == 0 && count($setting->filter_relationship_user_fields) == 0) {
      return $snap;
    }
    $user        = Auth::user();
    $user_scopes = $user->scopes;
    if (count(array_intersect($user_scopes, $setting->filter_user_disable_scopes)) == 0) {
      foreach ($setting->filter_user_fields as $filter_user_field) {
        $snap = $snap->whereHas($filter_user_field, function ($query) use ($user) {
          return $query->where('id', $user->id);
        });
      }
      foreach ($setting->filter_relationship_user_fields as $filter_relationship_user_field_key => $filter_relationship_user_field) {
        $snap = $snap->whereHas($filter_relationship_user_field, function ($query) use ($user) {
          return $query->where('id', $user->id);
        });
      }
    }
    return $snap;
  }

  public static function scopeFilterCheck($request, $setting)
  {

  }

  public static function getIdsFromModels($models, $id_key = 'id')
  {
    $ids = [];
    foreach ($models as $model) {
      $ids[] = $model->{$id_key};
    }
    return $ids;
  }

  public static function adminGroupFilterSnap($snap, $setting)
  {
    if (config('stone.admin_group') && $setting->admin_group) {
      $admin = Auth::user();
      if (config('stone.admin_blur')) {
        $admin_groups_key = 'cmser_groups';
      } else {
        $admin_groups_key = 'admin_groups';
      }
      if (!in_array($setting->admin_scope_boss, $admin->scopes)) {
        $admin_group_ids = self::getIdsFromModels($admin->{$admin_groups_key});
        $snap            = $snap->whereHas($admin_groups_key, function ($query) use ($admin_group_ids) {
          return $query->whereIn('id', $admin_group_ids);
        });
      }
    }
    return $snap;
  }

  public static function adminGroupStoreHandler($model, $setting)
  {
    if (config('stone.admin_group') && $setting->admin_group) {
      $admin = Auth::user();
      if (config('stone.admin_blur')) {
        $admin_groups_key = 'cmser_groups';
      } else {
        $admin_groups_key = 'admin_groups';
      }
      if (!in_array($setting->admin_scope_boss, $admin->scopes)) {
        $admin_group_ids = self::getIdsFromModels($admin->{$admin_groups_key});
        $model->{$admin_groups_key}()->sync($admin_group_ids);
      }
    }
  }

  //version_name=>所在version ex.audit_version
  //model_name=>本身model_name ex.audit_question
  //$model => 版本model ex.AuditQuestionVersion
  public static function getVersion($request, $version_name = null, $model_name = null, $resource = null, $order_by = null, $model = null)
  {
    $last_version = [];
    if ($request[$version_name . 's']) {
      $item_arr     = array_map('intval', explode(',', $request[$version_name . 's']));
      $last_version = $model::whereHas($version_name . 's', function ($query) use ($item_arr) {
        return $query->whereIn('id', $item_arr);
      })->where($model_name . '_id', $resource->id)->latest($order_by)->get();
      $last_version = count($last_version) ? $last_version[0] : null;
    } else {
      $last_version = $model::where($model_name . '_id', $resource->id)->latest($order_by)->get();
      $last_version = count($last_version) ? $last_version[0] : null;
    }
    return $last_version;
  }

  public static function setUUID($model, $setting)
  {
    if ($setting->uuid) {
      $model->{$setting->uuid_key} = Str::uuid();
    }
    return $model;
  }

  public static function countryCodeFilterSnap($snap, $setting)
  {
    if (config('stone.country_code') && $setting->country_code) {
      $user = Auth::user();
      if ($user && $user->country_code) {
        $snap = $snap->where('country_code', $user->country_code);
      }
    }
    return $snap;
  }

  public static function countryCodeStoreHandler($model, $setting)
  {
    if (config('stone.country_code') && $setting->country_code) {
      $user = Auth::user();
      if ($user && $user->country_code) {
        $model->country_code = $user->country_code;
        $model->save();
      }
    }
  }
}
