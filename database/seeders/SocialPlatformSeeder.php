<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SocialPlatformSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    DB::table('social_platforms')->insert([
      ['name' => 'Instagram', 'slug' => 'instagram', 'icon' => '/icons/social/instagram.svg'],
      ['name' => 'TikTok', 'slug' => 'tiktok', 'icon' => '/icons/social/tiktok.svg'],
      ['name' => 'YouTube', 'slug' => 'youtube', 'icon' => '/icons/social/youtube.svg'],
      ['name' => 'Spotify', 'slug' => 'spotify', 'icon' => '/icons/social/spotify.svg'],
      ['name' => 'Apple Music', 'slug' => 'apple-music', 'icon' => '/icons/social/apple-music.svg'],
    ]);
  }
}
