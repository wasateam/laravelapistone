<?php

namespace Wasateam\Laravelapistone\Helpers;

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
}
