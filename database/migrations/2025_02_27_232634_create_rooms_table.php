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
      $table->id(); // allready there

      $table->string('name');  // for text like "Meeting Room A"
      $table->enum('type', ['meeting', 'office', 'booth', 'open_world']);  // for pre-defined selection to choose from
      $table->integer('capacity');  // for integers like 8, 10, 16
      $table->integer('min_duration')->default(60);  // with a default of 60 minutes
      $table->decimal('price_per_hour', 8, 2);  // for rates with 2 decimals
      $table->boolean('is_active')->default(true);  // for boolean

      $table->timestamps(); // bereits vorgegeben
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
