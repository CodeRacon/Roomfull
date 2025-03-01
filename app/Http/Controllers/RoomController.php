<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;

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

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request)
  {
    // validate entries
    $validated = $request->validate([
      'name' => 'required|string|max:255',
      'type' => 'required|in:meeting,office,booth,open_world',
      'capacity' => 'required|integer|min:1',
      'min_duration' => 'required|integer|min:15',
      'price_per_hour' => 'required|numeric|min:0',
      'is_active' => 'boolean',
    ]);

    // create a new room
    $room = Room::create($validated);

    return redirect()->route('rooms.show', $room)
      ->with('success', 'Room created successfully!');
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
   * Update the specified resource in storage.
   */
  public function update(Request $request, Room $room)
  {
    // validate entries
    $validated = $request->validate([
      'name' => 'required|string|max:255',
      'type' => 'required|in:meeting,office,booth,open_world',
      'capacity' => 'required|integer|min:1',
      'min_duration' => 'required|integer|min:15',
      'price_per_hour' => 'required|numeric|min:0',
      'is_active' => 'boolean',
    ]);

    // update room
    $room->update($validated);

    return redirect()->route('rooms.show', $room)
      ->with('success', 'Room updated successfully!');
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
}
