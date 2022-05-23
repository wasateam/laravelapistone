<?php

namespace Wasateam\Laravelapistone\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * @group CountryData
 *
 */
class CountryDataController extends Controller
{
  /**
   * Mobile Country Code
   *
   */
  public function mobile_country_code(Request $request)
  {
    $country_json = json_decode(file_get_contents(base_path() . '/vendor/wasateam/laravelapistone/data/country.json'), true);
    $data         = [];
    foreach ($country_json as $country) {
      $data[] = [
        "countryCode"        => $country['countryCode'],
        "countryCallingCode" => $country['countryCallingCode'],
      ];
    }
    return $data;
  }
}
