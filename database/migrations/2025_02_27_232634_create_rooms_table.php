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
    Schema::create('rooms', function (Blueprint $table) {
      $table->id(); // Das ist bereits da

      $table->string('name');  // Für Text wie "Meeting Room A"
      $table->enum('type', ['meeting', 'office', 'booth', 'open_world']);  // Für vorgegebene Auswahlmöglichkeiten
      $table->integer('capacity');  // Für ganze Zahlen wie 8, 10, 16
      $table->integer('min_duration')->default(60);  // Mit Standardwert 60 Minuten
      $table->decimal('price_per_hour', 8, 2);  // Für Preise mit 2 Dezimalstellen
      $table->boolean('is_active')->default(true);  // Für ja/nein-Werte

      $table->timestamps(); // Das ist bereits da
    });
  }


  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('rooms');
  }
};
