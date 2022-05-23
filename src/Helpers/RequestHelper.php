<?php

namespace Wasateam\Laravelapistone\Helpers;

use Validator;

class RequestHelper
{
  public static function requiredFieldsCheck($requset, $fields)
  {
    foreach ($fields as $field) {
      if (!$requset->filled($field)) {
        throw new \Wasateam\Laravelapistone\Exceptions\FieldRequiredException($field);
      }
    }
  }

  public static function requestValidate($request, $rules, $messages = [])
  {
    $validator = Validator::make($request->all(), $rules);
    if ($validator->fails()) {
      throw new \Wasateam\Laravelapistone\Exceptions\RequestValidateException($validator);
    }
  }
}
