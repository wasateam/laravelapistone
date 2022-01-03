<?php

namespace Wasateam\Laravelapistone\Helpers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Wasateam\Laravelapistone\Exceptions\FindNoUserServicePlanException;
use Wasateam\Laravelapistone\Models\AcumaticaAccessToken;
use Wasateam\Laravelapistone\Models\UserServicePlan;

class AcumaticaHelper
{
  public static function createEquipment($user, $type = null, $brand = null, $serial_number = null, $purchase_date = null, $app = null)
  {
    $pin               = null;
    $token             = self::getToken($app);
    $post_url          = config('stone.acumatica.api_url') . "/FSEquipment";
    $customerId        = $user->customer_id;
    $user_service_plan = UserServicePlan
      ::where('user_id', $user->id)
      ->where('expired_at', '>=', Carbon::now())
      ->orderBy('created_at', 'desc')
      ->first();
    if (!$user_service_plan) {
      throw new FindNoUserServicePlanException;
    }
    if ($user_service_plan->pin_card) {
      $pin = $user_service_plan->pin_card->pin;
    }

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
          "value" => $brand,
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
        "value" => true,
      ],
      "Vehicle"            => [
        "value" => false,
      ],
    ];
    $response = Http::withHeaders([
      'Authorization' => "Bearer {$token}",
    ])->put($post_url, $post_data);
    return $response->json();
  }

  public static function deactiveEquipment($equipment_id, $serial_number)
  {
    $post_url          = config('stone.acumatica.api_url') . "/FSEquipment";
    $post_data = [
      'id'        => $equipment_id,
      'SerialNbr' => [
        'value' => $serial_number,
      ],
      'Status'    => [
        'value' => 'Suspended',
      ],
    ];

    $response = Http::withHeaders([
      'Authorization' => "Bearer {$token}",
    ])->put($post_url, $post_data);
    return $response->json();
  }

  public static function activeEquipment($equipment_id, $serial_number)
  {
    $post_url          = config('stone.acumatica.api_url') . "/FSEquipment";
    $post_data = [
      'id'        => $equipment_id,
      'SerialNbr' => [
        'value' => $serial_number,
      ],
      'Status'    => [
        'value' => 'Active',
      ],
    ];

    $response = Http::withHeaders([
      'Authorization' => "Bearer {$token}",
    ])->put($post_url, $post_data);
    return $response->json();
  }

  public static function createPINCode($customerId, $pin, $price_class)
  {
    $token     = self::getToken();
    $post_url  = config('stone.acumatica.api_url') . "/CustomerPINCode";
    $post_data = [
      'CustomerID' => [
        'value' => $customerId,
      ],
      'Details'    => [
        [
          "PIN"        => [
            'value' => $pin,
          ],
          'PriceClass' => [
            'value' => $price_class,
          ],
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
    $token    = self::getToken();
    $post_url = config('stone.acumatica.api_url') . "/Customer?\$filter=CustomerID eq '{$customerId}'&\$expand=PrimaryContact";
    $response = Http::withHeaders([
      'Authorization' => "Bearer {$token}",
    ])->get($post_url);
    return $response->json();
  }

  public static function createCustomer($class, $price_class, $user)
  {
    $token     = self::getToken();
    $post_url  = config('stone.acumatica.api_url') . "/Customer";
    $post_data = [
      'CustomerName'  => [
        'value' => $user->name,
      ],
      'CustomerClass' => [
        'value' => $class,
      ],
      'PriceClass'    => [
        'value' => $price_class,
      ],
      'MainContact'   => [
        'Email' => [
          'value' => $user->email,
        ],
      ],
      'PrimayContact' => [
        'DateOfBirth' => [
          'value' => $user->birth,
        ],
        'LastName'    => [
          'value' => $user->name,
        ],
        'Email'       => [
          'value' => $user->email,
        ],
      ],
    ];
    $response = Http::withHeaders([
      'Authorization' => "Bearer {$token}",
    ])->put($post_url, $post_data);

    # @Q@ 回存acu id
    $user->acumatica_id = $response->json()['id'];
    $user->save();
    return $response->json();
  }

  public static function updateUserCustomerId($user)
  {
    $token     = self::getToken();
    $post_url  = config('stone.acumatica.api_url') . "/Customer/ChangeID";
    $post_data = [
      "entity"     => [
        "id" => $user->acumatica_id,
      ],
      "parameters" => [
        "CustomerID" => [
          "value" => $user->customer_id,
        ],
      ],
    ];
    $response = Http::withHeaders([
      'Authorization' => "Bearer {$token}",
    ])->post($post_url, $post_data);
    return $response->json();
  }

  public static function getServiceOrder($order_no, $acu_app = null)
  {
    $token    = self::getToken($acu_app);
    $post_url = config('stone.acumatica.api_url') . '/ServiceOrder?$filter=ServiceOrderNbr eq ';

    $response = Http::withHeaders([
      'Authorization' => "Bearer {$token}",
    ])->get($post_url . "'{$order_no}'");
    return $response->json();
  }

  public static function getToken($acu_app = null)
  {
    if ($acu_app) {
      $client_id     = $acu_app->client_id;
      $client_secret = $acu_app->client_secret;
    } else {
      $client_id     = config('stone.acumatica.client_id');
      $client_secret = config('stone.acumatica.client_secret');
    }
    $username = config('stone.acumatica.username');
    $password = config('stone.acumatica.password');
    if ($acu_app) {
      $last_token = AcumaticaAccessToken::where('acumatica_app_id', $acu_app->id)->orderBy('created_at', 'desc')->first();
    } else {
      $last_token = AcumaticaAccessToken::latest()->first();
    }
    if ($last_token) {
      $expires_in = $last_token->expires_in ? $last_token->expires_in : 3600;
      $due        = Carbon::parse($last_token->created_at)->addSeconds($expires_in - 60);
      if ($due->isFuture()) {
        return $last_token->access_token;
      }
    }
    // @Q@ 待補 PRD URL
    $post_url = config('stone.acumatica.token_url');
    try {
      $response = Http::withBody("grant_type=password&client_id=" . $client_id . "&client_secret=" . $client_secret . "&username=" . $username . "&password=" . $password . "&scope=api", 'application/x-www-form-urlencoded')
        ->post($post_url);
      \Log::info($response->body());
      $last_token = new AcumaticaAccessToken;
      if ($acu_app) {
        $last_token->acumatica_app_id = $acu_app->id;
      }
      $last_token->access_token = $response->json()['access_token'];
      $last_token->expires_in   = $response->json()['expires_in'];
      $last_token->token_type   = $response->json()['token_type'];
      $last_token->save();
      return $response->json()['access_token'];
    } catch (\Throwable $th) {
      throw $th;
    }
  }
}
