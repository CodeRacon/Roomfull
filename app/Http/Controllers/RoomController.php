<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;
use App\Services\BookingService;


class RoomController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    $rooms = Room::all();

    return view('rooms.index', compact('rooms'));
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create()
  {
    return view('rooms.create');
  }

  protected function roomValidationRules()
  {
    return [
      'name' => 'required|string|max:255',
      'type' => 'required|in:meeting,office,booth,open_world',
      'capacity' => 'required|integer|min:1',
      'min_duration' => 'required|integer|min:15',
      'price_per_hour' => 'required|numeric|min:0',
      'price_per_day' => 'nullable|numeric|min:0',
      'price_per_week' => 'nullable|numeric|min:0',
      'discount_percentage' => 'nullable|numeric|min:0|max:100',
      'is_active' => 'boolean',
    ];
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request)
  {
    // validation with rules from helper-method
    $validated = $request->validate($this->roomValidationRules());

    // create Room
    $room = Room::create($validated);

    return redirect()->route('rooms.show', $room)
      ->with('success', 'Room created successfully!');
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, Room $room)
  {
    // re-use same validation-rules
    $validated = $request->validate($this->roomValidationRules());

    // update Room
    $room->update($validated);

    return redirect()->route('rooms.show', $room)
      ->with('success', 'Room updated successfully!');
  }

  /**
   * Display the specified resource.
   */
  public function show(Room $room)
  {
    return view('rooms.show', compact('room'));
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(Room $room)
  {
    return view('rooms.edit', compact('room'));
  }


  /**
   * Remove the specified resource from storage.
   */
  public function destroy(Room $room)
  {
    $room->delete();

    return redirect()->route('rooms.index')
      ->with('success', 'Room deleted successfully!');
  }


  /**
   * check room availability
   */
  public function checkAvailability(Request $request, BookingService $bookingService)
  {
    $validated = $request->validate([
      'room_id' => 'required|exists:rooms,id',
      'start_time' => 'required|date',
      'end_time' => 'required|date|after:start_time',
    ]);

    $room = Room::findOrFail($validated['room_id']);
    $isAvailable = $bookingService->isRoomAvailable(
      $room,
      $validated['start_time'],
      $validated['end_time']
    );

    $price = null;
    if ($isAvailable) {
      $price = $bookingService->calculatePrice(
        $room,
        $validated['start_time'],
        $validated['end_time']
      );
    }

    return response()->json([
      'available' => $isAvailable,
      'price' => $price,
    ]);
  }
}
