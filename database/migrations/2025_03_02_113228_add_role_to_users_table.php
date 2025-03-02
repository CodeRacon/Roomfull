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
    Schema::table('users', function (Blueprint $table) {
      // Füge das role-Feld hinzu mit 'customer' als Standardwert
      $table->string('role')->default('customer')->after('password');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::table('users', function (Blueprint $table) {
      // Entferne das role-Feld bei einem Rollback
      $table->dropColumn('role');
    });
  }
};
