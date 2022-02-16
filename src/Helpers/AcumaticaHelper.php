<?php

namespace Wasateam\Laravelapistone\Helpers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Wasateam\Laravelapistone\Exceptions\FindNoUserServicePlanException;
use Wasateam\Laravelapistone\Models\AcumaticaAccessToken;
use Wasateam\Laravelapistone\Models\UserServicePlan;

/**
 * 用來串接 Acumatica 服務的動作
 * 呼叫 API 前都要先呼叫 getToken 取得 Token
 * 再拿 Token 進行後續 API 動作
 *
 */
class AcumaticaHelper
{
  /**
   * 建立裝置
   * user 使用者物件
   * user_devie 使用者裝置
   * app AcumaticaApp 沒傳送會抓 .env的設定
   */
  public static function createEquipment($user, $user_device, $app = null)
  {
    $type              = $user_device->type;
    $brand             = $user_device->brand;
    $serial_number     = $user_device->serial_number;
    $model_number      = $user_device->model_number;
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
        "value" => $brand . " " . $model_number,
      ],
      "EquipmentType"      => [
        "value" => $type,
      ],
      "General"            => [
        "Manufacturer"   => [
          "value" => $brand,
        ],
        "RegisteredDate" => [
          "value" => $user_service_plan->created_at,
        ],
        "SalesDate"      => [
          "value" => $user_service_plan->created_at,
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
          "value" => $user_service_plan->created_at,
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
      "Attributes"         => [
        [
          "AttributeID" => [
            "value" => "MODEL",
          ],
          "Value"       => [
            "value" => $model_number,
          ],
        ],
      ],
    ];
    $response = Http::withHeaders([
      'Authorization' => "Bearer {$token}",
    ])->put($post_url, $post_data);
    return $response->json();
  }

  /**
   * 停用裝置
   * equipment_id 使用者裝置 Acumatica ID
   * serial_number 序號
   * app AcumaticaApp 沒傳送會抓 .env的設定
   */
  public static function deactiveEquipment($equipment_id, $serial_number, $app = null)
  {
    $token     = self::getToken($app);
    $post_url  = config('stone.acumatica.api_url') . "/FSEquipment";
    $post_data = [
      'id'     => $equipment_id,
      'Status' => [
        'value' => 'Suspended',
      ],
    ];
    $response = Http::withHeaders([
      'Authorization' => "Bearer {$token}",
    ])->put($post_url, $post_data);
    return $response->json();
  }

  /**
   * 啟用裝置
   * equipment_id 使用者裝置 Acumatica ID
   * serial_number 序號
   * app AcumaticaApp 沒傳送會抓 .env的設定
   */
  public static function activeEquipment($equipment_id, $serial_number, $app = null)
  {
    $token     = self::getToken($app);
    $post_url  = config('stone.acumatica.api_url') . "/FSEquipment";
    $post_data = [
      'id'     => $equipment_id,
      'Status' => [
        'value' => 'Active',
      ],
    ];

    $response = Http::withHeaders([
      'Authorization' => "Bearer {$token}",
    ])->put($post_url, $post_data);
    return $response->json();
  }

  /**
   * 建立 PINCode
   * customerId 使用者 Customer ID
   * pin PIN碼,由後台產生
   * price_class 方案金額-Acumatica提供可輸入值 EX: HC199
   */
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

  /**
   * 取得使用者資訊
   * customerId 使用者 Customer ID
   */
  public static function getCustomer($customerId)
  {
    $token    = self::getToken();
    $post_url = config('stone.acumatica.api_url') . "/Customer?\$filter=CustomerID eq '{$customerId}'&\$expand=PrimaryContact";
    $response = Http::withHeaders([
      'Authorization' => "Bearer {$token}",
    ])->get($post_url);
    return $response->json();
  }

  /**
   * 建立使用者
   * customerId 使用者 Customer ID
   * class 類型
   * price_class 方案金額-Acumatica提供可輸入值 EX: HC199
   * user 使用者 model
   */
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
        'Phone1Type' => [
          'value' => 'Cell',
        ],
        'Phone1' => [
          'value' => $user->tel,
        ],
      ],
      'PrimayContact' => [
        'DateOfBirth' => [
          'value' => Carbon::parse($user->birthday),
        ],
        'LastName'    => [
          'value' => $user->name,
        ],
        'Email'       => [
          'value' => $user->email,
        ],
        'Phone1Type'       => [
          'value' => 'Cell',
        ],
        'Phone1'       => [
          'value' => $user->tel,
        ],
      ],
    ];
    $response = Http::withHeaders([
      'Authorization' => "Bearer {$token}",
    ])->put($post_url, $post_data);
    $user->acumatica_id = $response->json()['id'];
    $user->save();
    return $response->json();
  }

  /**
   * 更新使用者id資訊
   * user 使用者
   */
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

  /**
   * 更新使用者資訊
   * user 使用者
   */
  public static function updateCustomer($user)
  {
    $token     = self::getToken();
    $post_url  = config('stone.acumatica.api_url') . "/Customer";
    $post_data = [
      "id" => $user->acumatica_id,
      "CustomerName" => [
        "value" => $user->name,
      ],
      "MainContact" => [
        "Email" => [
          "value" => $user->email
        ]
      ],
      "PrimayContact" => [
        "DateOfBirth" => [
          "value" => $user->birthday
        ],
        "LastName" => [
          "value" => $user->name
        ],
        "Email" => [
          "value" => $user->email
        ]
      ]
    ];
    $response = Http::withHeaders([
      'Authorization' => "Bearer {$token}",
    ])->put($post_url, $post_data);
    return $response->json();
  }

  /**
   * 取得訂單資訊
   * order_no 訂單名稱
   * app AcumaticaApp 沒傳送會抓 .env的設定
   */
  public static function getServiceOrder($order_no, $app = null)
  {
    $token    = self::getToken($app);
    $post_url = config('stone.acumatica.api_url') . '/ServiceOrder?$filter=ServiceOrderNbr eq ';

    $response = Http::withHeaders([
      'Authorization' => "Bearer {$token}",
    ])->get($post_url . "'{$order_no}'");
    return $response->json();
  }

  /**
   * 取得 Acumatica Token
   * 取得 Token 後才能做後續 API 動作
   * app AcumaticaApp 沒傳送會抓 .env的設定
   */
  public static function getToken($app = null)
  {
    if ($app) {
      $client_id     = $app->client_id;
      $client_secret = $app->client_secret;
    } else {
      $client_id     = config('stone.acumatica.client_id');
      $client_secret = config('stone.acumatica.client_secret');
    }
    $username = config('stone.acumatica.username');
    $password = config('stone.acumatica.password');
    if ($app) {
      $last_token = AcumaticaAccessToken::where('acumatica_app_id', $app->id)->orderBy('created_at', 'desc')->first();
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
    $post_url = config('stone.acumatica.token_url');
    try {
      $response = Http::withBody("grant_type=password&client_id=" . $client_id . "&client_secret=" . $client_secret . "&username=" . $username . "&password=" . $password . "&scope=api", 'application/x-www-form-urlencoded')
        ->post($post_url);
      \Log::info($response->body());
      $last_token = new AcumaticaAccessToken;
      if ($app) {
        $last_token->acumatica_app_id = $app->id;
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
