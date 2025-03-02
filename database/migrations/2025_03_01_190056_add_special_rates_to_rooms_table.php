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
    Schema::table('rooms', function (Blueprint $table) {

      $table->decimal('price_per_day', 8, 2)->nullable();
      $table->decimal('price_per_week', 8, 2)->nullable();
      $table->decimal('discount_percentage', 5, 2)->nullable(); // e.g. 25.50 for 25,5%
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::table('rooms', function (Blueprint $table) {
      // remove columns
      $table->dropColumn(['price_per_day', 'price_per_week', 'discount_percentage']);
    });
  }
};
