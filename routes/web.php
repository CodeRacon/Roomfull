<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\BookingController;
use Illuminate\Support\Facades\Auth;


Route::get('/', function () {
  return view('welcome');
});

Route::resource('rooms', RoomController::class);

// Booking-Routes
Route::middleware('auth')->group(function () {
  Route::resource('bookings', BookingController::class)
    ->except(['edit', 'update', 'destroy']);

  Route::patch('bookings/{booking}/cancel', [BookingController::class, 'cancel'])
    ->name('bookings.cancel');
});

// availability-check via AJAX
Route::post('rooms/check-availability', [RoomController::class, 'checkAvailability'])
  ->name('rooms.check-availability');

// Admin-Routes
Route::middleware('auth')->prefix('admin')->group(function () {
  Route::get('/', function () {
    /** @var \App\Models\User $user */
    $user = Auth::user();
    if (!$user->isAdmin()) {
      return redirect('/')->with('error', 'Zugriff verweigert. Du benötigst Admin-Rechte.');
    }
    return view('admin.dashboard');
  })->name('admin.dashboard');
});
