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
    Schema::create('artist_social_links', function (Blueprint $table) {
      $table->id();
      $table->foreignId('artist_id')->constrained()->onDelete('cascade');
      $table->foreignId('social_platform_id')->constrained()->onDelete('cascade');
      $table->string('url');
      $table->timestamps();

      $table->unique(['artist_id', 'social_platform_id']); // 1 seul lien par plateforme/artiste
    });

  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('artist_social_links');
  }
};
