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
    Schema::create('live_sessions', function (Blueprint $table) {
      $table->id();
      $table->string('title');
      $table->string('slug')->unique();
      $table->foreignId('artist_id')->constrained()->cascadeOnDelete();
      $table->string('video_url');
      $table->string('thumbnail')->nullable();
      $table->date('recorded_at')->nullable();
      $table->date('published_at')->nullable();
      $table->text('description')->nullable();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('live_sessions');
  }
};
