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

      // Fremdschlüssel
      $table->foreignId('room_id')->constrained(); // Verknüpft mit rooms.id
      $table->foreignId('user_id')->constrained(); // Verknüpft mit users.id

      // Buchungsdetails
      $table->dateTime('start_time');
      $table->dateTime('end_time');
      $table->enum('status', ['pending', 'confirmed', 'cancelled'])->default('pending');
      $table->decimal('price', 8, 2);
      $table->text('notes')->nullable(); // Nullable bedeutet, dass dieser Wert NULL sein darf

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
