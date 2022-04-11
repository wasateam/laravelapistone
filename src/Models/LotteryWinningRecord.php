<?php
namespace Wasateam\Laravelapistone\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LotteryWinningRecord extends Model
{
  use HasFactory;
  use \Illuminate\Database\Eloquent\SoftDeletes;

  public function lottery_participant()
  {
    return $this->belongsTo(LotteryParticipant::class, 'lottery_participant_id');
  }

  public function lottery_prize()
  {
    return $this->belongsTo(LotteryPrize::class, 'lottery_prize_id');
  }
}
