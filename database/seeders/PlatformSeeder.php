<?php

namespace Database\Seeders;

use App\Models\Platform;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PlatformSeeder extends Seeder
{
    public function run(): void
    {
        $platforms = [
            // Réseaux sociaux
            [
                'name' => 'Instagram',
                'slug' => 'instagram',
                'type' => 'social',
                'icon' => 'icons/platforms/instagram.svg',
            ],
            [
                'name' => 'X',
                'slug' => 'x',
                'type' => 'social',
                'icon' => 'icons/platforms/x.svg',
            ],
            [
                'name' => 'YouTube',
                'slug' => 'youtube',
                'type' => 'social',
                'icon' => 'icons/platforms/youtube.svg',
            ],
            [
                'name' => 'TikTok',
                'slug' => 'tiktok',
                'type' => 'social',
                'icon' => 'icons/platforms/tiktok.svg',
            ],

            // Plateformes musicales
            [
                'name' => 'Spotify',
                'slug' => 'spotify',
                'type' => 'music',
                'icon' => 'icons/platforms/spotify.svg',
            ],
            [
                'name' => 'Apple Music',
                'slug' => 'apple-music',
                'type' => 'music',
                'icon' => 'icons/platforms/apple-music.svg',
            ],
            [
                'name' => 'Deezer',
                'slug' => 'deezer',
                'type' => 'music',
                'icon' => 'icons/platforms/deezer.svg',
            ],
        ];

        foreach ($platforms as $platform) {
            Platform::updateOrCreate(
                ['slug' => $platform['slug']],
                $platform
            );
        }
    }
}
