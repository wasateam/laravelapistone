<?php

namespace Wasateam\Laravelapistone\Services;

use Illuminate\Support\Facades\Http;

class RecaptchaService
{
  public static function verify_v2($token, $secret)
  {
    $response = Http::withHeaders([
      "Content-Type" => "application/x-www-form-urlencoded",
      "charset"      => "utf-8",
    ])
      ->withBody("secret=" . $secret . "&response=" . $token, 'application/x-www-form-urlencoded')
      ->post('https://www.google.com/recaptcha/api/siteverify');
    if (!$response['success']) {
      throw new \Wasateam\Laravelapistone\Exceptions\RecaptchaException();
    }
  }
}
