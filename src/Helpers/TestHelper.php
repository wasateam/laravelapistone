<?php

namespace Wasateam\Laravelapistone\Helpers;

class TestHelper
{
  public static function test_all_stone($tester)
  {
    if (config('stone.mode') == 'cms') {
      self::test_cms($tester);
    } else if (config('stone.mode') == 'webapi') {
      self::test_webapi($tester);
    }
  }

  public static function test_cms($tester)
  {

  }

  public static function test_webapi($tester)
  {
    error_log('ok');
  }
}
