<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::create('artist_live_session_participant', function (Blueprint $table) {
      $table->id();
      $table->foreignId('artist_id')->constrained()->cascadeOnDelete();
      $table->foreignId('live_session_id')->constrained()->cascadeOnDelete();
      $table->timestamps();

      $table->unique(['artist_id', 'live_session_id']); // un artiste ne participe qu'une fois à une session
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('artist_live_session_participant');
  }
};
