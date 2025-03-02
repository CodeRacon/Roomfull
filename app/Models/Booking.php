<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Booking extends Model
{
  protected $fillable = [
    'room_id',
    'user_id',
    'start_time',
    'end_time',
    'status',
    'price',
    'notes'
  ];

  // relations
  public function room()
  {
    return $this->belongsTo(Room::class);
  }

  public function user()
  {
    return $this->belongsTo(User::class);
  }

  // Accessors & usefull Methods
  public function getDurationInHours()
  {
    $start = new Carbon($this->start_time);
    $end = new Carbon($this->end_time);

    return $end->diffInHours($start);
  }

  public function getDurationInDays()
  {
    $start = new Carbon($this->start_time);
    $end = new Carbon($this->end_time);

    return $end->diffInDays($start);
  }

  // Helper-Method to proof if booking can be canceled
  public function canBeCancelled()
  {
    return $this->status !== 'cancelled' &&
      Carbon::now()->lt(new Carbon($this->start_time));
  }
}
