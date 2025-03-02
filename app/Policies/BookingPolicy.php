<?php

namespace App\Policies;

use App\Models\Booking;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class BookingPolicy
{
  use HandlesAuthorization;

  /**
   * determines, whether user can view booking
   */
  public function view(User $user, Booking $booking): Response
  {
    return $user->id === $booking->user_id
      ? Response::allow()
      : Response::deny('Sie sind nicht berechtigt, diese Buchung anzusehen.');
  }

  /**
   * determines, whether user can cancel booking
   */
  public function cancel(User $user, Booking $booking): Response
  {
    return $user->id === $booking->user_id && $booking->canBeCancelled()
      ? Response::allow()
      : Response::deny('Sie sind nicht berechtigt, diese Buchung zu stornieren.');
  }
}
