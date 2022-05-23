<?php

namespace Wasateam\Laravelapistone\Helpers;

class StoneData
{
  public static function country()
  {
    return file_get_contents(base_path() . '/vendor/wasateam/laravelapistone/data/country.json');
  }
}
