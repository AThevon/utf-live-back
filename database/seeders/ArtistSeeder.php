<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Artist;
use Illuminate\Support\Str;

class ArtistSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $artists = [
      [
        'name' => 'NIYMA',
        'slug' => Str::slug('NIYMA'),
        'bio' => '~',
      ],
      [
        'name' => 'Jane et Les Autres',
        'slug' => Str::slug('Jane et Les Autres'),
        'bio' => '~',
      ],
      [
        'name' => 'Cazlab',
        'slug' => Str::slug('Cazlab'),
        'bio' => '~',
      ],
      [
        'name' => 'Jeune JR',
        'slug' => Str::slug('Jeune JR'),
        'bio' => '~',
      ],
    ];

    foreach ($artists as $artist) {
      Artist::updateOrCreate(['slug' => $artist['slug']], $artist);
    }
  }
}
