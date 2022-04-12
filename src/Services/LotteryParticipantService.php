<?php

namespace Wasateam\Laravelapistone\Services;

use Google\Cloud\PubSub\MessageBuilder;
use Google\Cloud\PubSub\PubSubClient;
use Wasateam\Laravelapistone\Helpers\ModelHelper;
use Wasateam\Laravelapistone\Helpers\StrHelper;
use Wasateam\Laravelapistone\Models\LotteryParticipant;

class LotteryParticipantService
{

  public function __construct()
  {
  }

  public function store($request, $controller, $id)
  {
    if (config('stone.lottery.participant')) {
      if (config('stone.lottery.participant.create_buffer')) {
        if (config('stone.lottery.participant.create_buffer.service') == 'pubsub') {
          $pubsub = new PubSubClient([
            'projectId'   => env('STONE_LOTTERY_PUBSUB_PROJECT_ID'),
            'keyFilePath' => base_path(env('GOOGLE_APPLICATION_CREDENTIALS')),
          ]);
          $topic   = $pubsub->topic(env('STONE_LOTTERY_PUBSUB_TOPIC'));
          $message = json_encode([
            'id_number' => $request->id_number,
            'birthday'  => $request->birthday,
            'email'     => $request->email,
            'mobile'    => $request->mobile,
          ]);
          if (config('stone.lottery.participant.create_buffer.encode')) {
            $message = StrHelper::strEncodeAES($message, env('STONE_LOTTERY_HASH_KEY'), env('STONE_LOTTERY_HASH_IV'));
          }
          $topic->publish((new MessageBuilder)->setData($message)->build());
        }
      } else {
        return ModelHelper::ws_StoreHandler($controller, $request, $id);
      }
    }
  }

  public function storeFromPubSub()
  {
    $pubsub = new PubSubClient([
      'projectId'   => env('STONE_LOTTERY_PUBSUB_PROJECT_ID'),
      'keyFilePath' => base_path(env('GOOGLE_APPLICATION_CREDENTIALS')),
    ]);

    $subscription = $pubsub->subscription(env('STONE_LOTTERY_PUBSUB_SUB'));
    $messages     = $subscription->pull();

    foreach ($messages as $message) {
      \Log::info($message->data());

      $data = json_decode(StrHelper::strDecodeAES($message->data(), env('STONE_LOTTERY_HASH_KEY'), env('STONE_LOTTERY_HASH_IV')));

      $model            = new LotteryParticipant;
      $model->id_number = $data->id_number;
      $model->birthday  = $data->birthday;
      $model->email     = $data->email;
      $model->mobile    = $data->mobile;
      $model->save();

      $subscription->acknowledge($message);
    }

  }
}
