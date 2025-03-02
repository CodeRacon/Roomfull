<?php

namespace App\Services;

use App\Models\Room;
use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class BookingService
{
  /**
   * proofs if a room is available at a given time
   */
  public function isRoomAvailable(Room $room, $startTime, $endTime)
  {
    // check if room is active
    if (!$room->is_active) {
      return false;
    }

    // create Carbon-Instances
    $start = new Carbon($startTime);
    $end = new Carbon($endTime);

    // check duration of booking
    $durationMinutes = $end->diffInMinutes($start);
    if ($durationMinutes < $room->min_duration) {
      return false;
    }

    // check for conflicts with already made bookings
    $conflictingBookings = Booking::where('room_id', $room->id)
      ->where('status', '!=', 'cancelled')
      ->where(function ($query) use ($start, $end) {
        $query->whereBetween('start_time', [$start, $end])
          ->orWhereBetween('end_time', [$start, $end])
          ->orWhere(function ($query) use ($start, $end) {
            $query->where('start_time', '<', $start)
              ->where('end_time', '>', $end);
          });
      })
      ->count();

    return $conflictingBookings === 0;
  }

  /**
   * Calculates the price for a booking based on the duration and price options of the room   
   */
  public function calculatePrice(Room $room, $startTime, $endTime)
  {
    $start = new Carbon($startTime);
    $end = new Carbon($endTime);

    $durationHours = $end->diffInHours($start);
    $durationDays = $end->diffInDays($start);
    $durationWeeks = floor($durationDays / 7);

    $price = 0;

    // Apply weekly price if available and reasonable
    if ($room->price_per_week && $durationWeeks >= 1) {
      $price += $durationWeeks * $room->price_per_week;
      $remainingDays = $durationDays % 7;
    } else {
      $remainingDays = $durationDays;
    }

    // Apply dayly price if available and reasonable
    if ($room->price_per_day && $remainingDays >= 1) {
      $price += $remainingDays * $room->price_per_day;
      $remainingHours = $durationHours % 24;
    } else {
      $remainingHours = $durationHours;
      // If no daily price, but days are left, calculate on an hourly basis
      if ($remainingDays >= 1) {
        $remainingHours = $durationHours;
      }
    }

    // Calculate remaining hours with hourly price
    $price += $remainingHours * $room->price_per_hour;

    // Apply discount, if available
    if ($room->discount_percentage) {
      $discount = $price * ($room->discount_percentage / 100);
      $price -= $discount;
    }

    return round($price, 2);
  }

  /**
   * creates a new booking
   */
  public function createBooking(array $data)
  {
    return DB::transaction(function () use ($data) {
      $room = Room::findOrFail($data['room_id']);

      // check availability 
      if (!$this->isRoomAvailable($room, $data['start_time'], $data['end_time'])) {
        throw new \Exception('Room is not available for the selected time period.');
      }

      // calculate price
      $price = $this->calculatePrice($room, $data['start_time'], $data['end_time']);

      // creates booking
      return Booking::create([
        'room_id' => $data['room_id'],
        'user_id' => $data['user_id'],
        'start_time' => $data['start_time'],
        'end_time' => $data['end_time'],
        'status' => $data['status'] ?? 'pending',
        'price' => $price,
        'notes' => $data['notes'] ?? null,
      ]);
    });
  }
}
