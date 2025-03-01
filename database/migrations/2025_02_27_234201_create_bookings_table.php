<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::create('bookings', function (Blueprint $table) {
      $table->id();

      // Foreign Key
      $table->foreignId('room_id')->constrained(); // connected with rooms.id
      $table->foreignId('user_id')->constrained(); // connected with users.id

      // Booking-Deatails
      $table->dateTime('start_time');
      $table->dateTime('end_time');
      $table->enum('status', ['pending', 'confirmed', 'cancelled'])->default('pending');
      $table->decimal('price', 8, 2);
      $table->text('notes')->nullable(); // nullable means, value can be Null

      $table->timestamps();
    });
  }


  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('bookings');
  }
};
