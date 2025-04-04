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
        Schema::create('platforms', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->enum('type', ['social', 'music'])->default('social');
            $table->string('icon')->nullable();
            $table->timestamps();
        });

        Schema::create('artist_platform_links', function (Blueprint $table) {
            $table->id();
            $table->foreignId('artist_id')->constrained()->onDelete('cascade');
            $table->foreignId('platform_id')->constrained()->onDelete('cascade');
            $table->string('url');
            $table->timestamps();

            $table->unique(['artist_id', 'platform_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('artist_platform_links');
        Schema::dropIfExists('platforms');
    }
};
