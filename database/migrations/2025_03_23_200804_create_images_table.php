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
    Schema::create('images', function (Blueprint $table) {
      $table->id();
      $table->string('path'); // ex: 'artists/xxx.png'
      $table->string('alt')->nullable(); // pour l’accessibilité
      $table->nullableMorphs('imageable'); // morph : artist, session, etc.
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('images');
  }
};
