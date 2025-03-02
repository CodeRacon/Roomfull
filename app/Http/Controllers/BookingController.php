<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Booking;
use App\Services\BookingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class BookingController extends Controller
{
  use AuthorizesRequests;

  protected $bookingService;

  public function __construct(BookingService $bookingService)
  {
    $this->bookingService = $bookingService;
  }

  /**
   * Zeigt alle Buchungen des aktuellen Benutzers
   */
  public function index()
  {
    // Alternative ohne Beziehungsmethode, falls IDE-Fehler weiterhin besteht
    $userId = Auth::id();
    $bookings = Booking::where('user_id', $userId)
      ->with('room')
      ->latest()
      ->get();

    return view('bookings.index', compact('bookings'));
  }

  /**
   * Zeigt das Formular zum Erstellen einer neuen Buchung
   */
  public function create(Request $request)
  {
    $roomId = $request->query('room_id');
    $room = $roomId ? Room::findOrFail($roomId) : null;
    $rooms = Room::where('is_active', true)->get();

    return view('bookings.create', compact('rooms', 'room'));
  }

  /**
   * Speichert eine neue Buchung
   */
  public function store(Request $request)
  {
    $validated = $request->validate([
      'room_id' => 'required|exists:rooms,id',
      'start_time' => 'required|date|after_or_equal:now',
      'end_time' => 'required|date|after:start_time',
      'notes' => 'nullable|string',
    ]);

    try {
      $booking = $this->bookingService->createBooking([
        'room_id' => $validated['room_id'],
        'user_id' => Auth::id(),
        'start_time' => $validated['start_time'],
        'end_time' => $validated['end_time'],
        'notes' => $validated['notes'] ?? null,
      ]);

      return redirect()->route('bookings.show', $booking)
        ->with('success', 'Booking created successfully!');
    } catch (\Exception $e) {
      return back()->withInput()
        ->with('error', 'Unable to create booking: ' . $e->getMessage());
    }
  }

  /**
   * Zeigt die Details einer Buchung
   */
  public function show(Booking $booking)
  {
    $this->authorize('view', $booking);

    return view('bookings.show', compact('booking'));
  }

  /**
   * Buchung stornieren
   */
  public function cancel(Booking $booking)
  {
    $this->authorize('cancel', $booking);

    if (!$booking->canBeCancelled()) {
      return back()->with('error', 'This booking cannot be cancelled.');
    }

    $booking->update(['status' => 'cancelled']);

    return redirect()->route('bookings.index')
      ->with('success', 'Booking cancelled successfully!');
  }
}
