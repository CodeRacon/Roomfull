<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
  use HasFactory;

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'name',
    'type',
    'capacity',
    'min_duration',
    'price_per_hour',
    'price_per_day',
    'price_per_week',
    'discount_percentage',
    'is_active',
  ];


  /**
   * Get the bookings for the room.
   */
  public function bookings()
  {
    return $this->hasMany(Booking::class);
  }
}
