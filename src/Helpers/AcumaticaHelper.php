<?php

namespace Wasateam\Laravelapistone\Helpers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Wasateam\Laravelapistone\Models\AcumaticaAccessToken;

class AcumaticaHelper
{
  public static function createEquipment($customerId, $pin, $type, $purchase_date, $serial_number)
  {
    $token = self::getToken();
    $mode  = config('stone.acumatica.mode');
    // @Q@ 待補 PRD URL
    $post_url  = $mode == 'prd' ? "https://dev02.clouderp.com.tw/HSN/entity/wasateam/20.200.001/FSEquipment" : "https://dev02.clouderp.com.tw/HSN/entity/wasateam/20.200.001/FSEquipment";
    $post_data = [
      'CustomerCustomerID' => [
        'value' => $customerId,
      ],
      'CustomerOwnerID'    => [
        'value' => $customerId,
      ],
      'PINCode'            => [
        'value' => $pin,
      ],
      "Description"        => [
        "value" => "AOI",
      ],
      "EquipmentType"      => [
        "value" => $type,
      ],
      "General"            => [
        "Manufacturer" => [
          "value" => "ACER",
        ],
      ],
      "LocationType"       => [
        "value" => "Customer",
      ],
      "OwnerType"          => [
        "value" => "Customer",
      ],
      "PurchaseInfo"       => [
        "PurchaseDate" => [
          "value" => $purchase_date,
        ],
      ],
      "ResourceEquipment"  => [
        "value" => false,
      ],
      "SerialNbr"          => [
        "value" => $serial_number,
      ],
      "Status"             => [
        "value" => "Active",
      ],
      "TargetEquipment"    => [
        "value" => "Active",
      ],
      "Vehicle"            => [
        "value" => false,
      ],
      "custom"             => [],
    ];
    $response = Http::withHeaders([
      'Authorization' => "Bearer {$token}",
    ])->put($post_url, $post_data);
    return $response->json();
  }

  public static function createPINCode($customerId, $pin, $price_class)
  {
    $token = self::getToken();
    $mode  = config('stone.acumatica.mode');
    // @Q@ 待補 PRD URL
    $post_url  = $mode == 'prd' ? "https://dev02.clouderp.com.tw/HSN/entity/wasateam/20.200.001/CustomerPINCode" : "https://dev02.clouderp.com.tw/HSN/entity/wasateam/20.200.001/CustomerPINCode";
    $post_data = [
      'CustomerID' => [
        'value' => $customerId,
      ],
      'Details'    => [
        "PIN"        => [
          'value' => $pin,
        ],
        'PriceClass' => [
          'value' => $price_class,
        ],
      ],
    ];
    $response = Http::withHeaders([
      'Authorization' => "Bearer {$token}",
    ])->put($post_url, $post_data);
    return $response->json();
  }

  public static function getCustomer($customerId)
  {
    $token = self::getToken();
    $mode  = config('stone.acumatica.mode');
    // @Q@ 待補 PRD URL
    $post_url = $mode == 'prd' ? "https://uat-hsnerp.hsnservice.com/HSN_UAT/entity/Default/20.200.001/Customer?\$filter=CustomerID eq '{$customerId}'&\$expand=PrimaryContact" : "https://uat-hsnerp.hsnservice.com/HSN_UAT/entity/Default/20.200.001/Customer?\$filter=CustomerID eq '{$customerId}'&\$expand=PrimaryContact";
    $response = Http::withHeaders([
      'Authorization' => "Bearer {$token}",
    ])->get($post_url);
    return $response->json();
  }

  public static function createCustomer($name, $class, $price_class, $email, $birth)
  {
    $token = self::getToken();
    $mode  = config('stone.acumatica.mode');
    // @Q@ 待補 PRD URL
    $post_url  = $mode == 'prd' ? "https://dev02.clouderp.com.tw/HSN/entity/wasateam/20.200.001/Customer" : "https://dev02.clouderp.com.tw/HSN/entity/wasateam/20.200.001/Customer";
    $post_data = [
      'CustomerName'   => [
        'value' => $name,
      ],
      'CustomerClass'  => [
        'value' => $class,
      ],
      'PriceClass'     => [
        'value' => $price_class,
      ],
      'MainContact'    => [
        'Email' => [
          'value' => $email,
        ],
      ],
      'PrimaryContact' => [
        'DateOfBirth' => [
          'value' => $birth,
        ],
        'LastName'    => [
          'value' => $name,
        ],
        'Email'       => [
          'value' => $email,
        ],
      ],
    ];
    $response = Http::withHeaders([
      'Authorization' => "Bearer {$token}",
    ])->put($post_url, $post_data);
    return $response->json();
  }

  public static function changeCustomerId($id, $customerId)
  {

    $token = self::getToken();
    $mode  = config('stone.acumatica.mode');
    // @Q@ 待補 PRD URL
    $post_url  = $mode == 'prd' ? "https://dev02.clouderp.com.tw/HSN/entity/wasateam/20.200.001/Customer/ChangeID" : "https://dev02.clouderp.com.tw/HSN/entity/wasateam/20.200.001/Customer/ChangeID";
    $post_data = [
      "entity"     => [
        "id" => $id,
      ],
      "parameters" => [
        "CustomerID" => [
          "value" => $customerId,
        ],
      ],
    ];
    $response = Http::withHeaders([
      'Authorization' => "Bearer {$token}",
    ])->post($post_url, $post_data);
    return $response->json();
  }

  public static function getServiceOrder($order_no)
  {
    $token = self::getToken();
    $mode  = config('stone.acumatica.mode');
    // @Q@ 待補 PRD URL
    $post_url = $mode == 'prd' ? 'https://uat-hsnerp.hsnservice.com/HSN_UAT/entity/Default/20.200.001/ServiceOrder?$filter=ServiceOrderNbr eq ' : 'https://uat-hsnerp.hsnservice.com/HSN_UAT/entity/Default/20.200.001/ServiceOrder?$filter=ServiceOrderNbr eq ';

    $response = Http::withHeaders([
      'Authorization' => "Bearer {$token}",
    ])->get($post_url . "'{$order_no}'");
    return $response->json();
  }

  public static function getToken()
  {
    $client_id     = config('stone.acumatica.client_id');
    $client_secret = config('stone.acumatica.client_secret');
    $username      = config('stone.acumatica.username');
    $password      = config('stone.acumatica.password');
    $mode          = config('stone.acumatica.mode');
    $last_token    = AcumaticaAccessToken::latest()->first();
    if ($last_token) {
      $expires_in = $last_token->expires_in ? $last_token->expires_in : 3600;
      $due        = Carbon::parse($last_token->created_at)->addSeconds($expires_in - 60);
      if ($due->isFuture()) {
        return $last_token->access_token;
      }
    }
    // @Q@ 待補 PRD URL
    $post_url = $mode == 'prd' ? 'https://uat-hsnerp.hsnservice.com/HSN_UAT/identity/connect/token' : 'https://uat-hsnerp.hsnservice.com/HSN_UAT/identity/connect/token';
    try {
      $response = Http::withBody("grant_type=password&client_id=" . $client_id . "&client_secret=" . $client_secret . "&username=" . $username . "&password=" . $password . "&scope=api", 'application/x-www-form-urlencoded')
        ->post($post_url);
      $last_token               = new AcumaticaAccessToken;
      $last_token->access_token = $response->json()['access_token'];
      $last_token->expires_in   = $response->json()['expires_in'];
      $last_token->token_type   = $response->json()['token_type'];
      $last_token->save();
      return $response->json()['access_token'];
      return $token;
    } catch (\Throwable $th) {
      throw $th;
    }
  }

}
