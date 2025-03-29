<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class YoutubeService
{
  public function fetchSnippet(string $videoId): ?array
  {
    $key = config('services.youtube.key');

    $response = Http::get('https://www.googleapis.com/youtube/v3/videos', [
      'part' => 'snippet',
      'id' => $videoId,
      'key' => $key,
    ]);

    return $response->successful() ? $response->json('items.0.snippet') : null;
  }
}
