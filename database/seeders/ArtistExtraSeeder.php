<?php

namespace Database\Seeders;

use App\Models\Artist;
use App\Models\LiveSession;
use App\Models\SocialPlatform;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;

class ArtistExtraSeeder extends Seeder
{
  public function run(): void
  {
    $artists = Artist::all();
    $platforms = SocialPlatform::all();
    $genres = ['Rap', 'Pop', 'Rock', 'Jazz', 'Électro', 'Chill', 'RnB', 'Classique', 'Reggae', 'Indie'];


    foreach ($artists as $artist) {
      // Live Sessions
      LiveSession::updateOrCreate([
        'slug' => Str::slug($artist->name . ' Live Session'),
      ], [
        'title' => $artist->name . ' Live Session',
        'genre' => Arr::random($genres),
        'artist_id' => $artist->id,
        'video_url' => 'https://www.youtube.com/embed/KznVKreSaDc?si=Vy2iBTL10g96taN9',
        'description' => 'Session live exclusive de ' . $artist->name,
        'published_at' => now()->subDays(rand(0, 100)),
      ]);

      // Réseaux Sociaux (2 plateformes aléatoires)
      $samplePlatforms = $platforms->random(2);

      foreach ($samplePlatforms as $platform) {
        DB::table('artist_social_links')->updateOrInsert([
          'artist_id' => $artist->id,
          'social_platform_id' => $platform->id,
        ], [
          'url' => 'https://' . $platform->slug . '.com/' . Str::slug($artist->name),
          'created_at' => now(),
          'updated_at' => now(),
        ]);
      }
    }
  }
}
