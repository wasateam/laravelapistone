<?php

namespace Wasateam\Laravelapistone\Helpers;

class EncryptHelper
{
  public static function wasa_mcrypt_decrypt($str, $hash_key, $hash_iv, $type = "aes-128-cbc")
  {

    return openssl_decrypt($str, $type, $hash_key, OPENSSL_RAW_DATA | OPENSSL_NO_PADDING, $hash_iv);

  }
}
