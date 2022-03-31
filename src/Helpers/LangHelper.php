<?php

namespace Wasateam\Laravelapistone\Helpers;

use Wasateam\Laravelapistone\Helpers\SpareHelper;

class LangHelper
{
  public static function getLangFromCountryCode($country_code)
  {
    $country_code_lang = SpareHelper::countryCodeLang();
    return ($country_code && $country_code_lang[$country_code]) ? $country_code_lang[$country_code] : 'en';
  }
}
