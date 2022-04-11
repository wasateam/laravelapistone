<?php

namespace Wasateam\Laravelapistone\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LotteryWinningRecord extends JsonResource
{
  /**
   * Transform the resource into an array.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
   */
  public function toArray($request)
  {
    if (config('stone.mode') == 'cms') {
      $res = [
        'id'                  => $this->id,
        'updated_at'          => $this->updated_at,
        'created_at'          => $this->created_at,
        'lottery_participant' => new LotteryParticipant_R1($this->lottery_participant),
        'lottery_prize'       => new LotteryPrize_R1($this->lottery_prize),
      ];
    } else if (config('stone.mode') == 'webapi') {
      $res = [
        'id'                  => $this->id,
        'updated_at'          => $this->updated_at,
        'created_at'          => $this->created_at,
        'lottery_participant' => new LotteryParticipant_R1($this->lottery_participant),
        'lottery_prize'       => new LotteryPrize_R1($this->lottery_prize),
      ];
    }
    return $res;
  }
}
