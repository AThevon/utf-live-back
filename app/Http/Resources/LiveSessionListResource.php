<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LiveSessionListResource extends JsonResource
{
  public function toArray($request): array
  {
    $thumbnail = $this->artist->images->firstWhere('is_thumbnail', true);

    return [
      'id' => $this->id,
      'title' => $this->title,
      'slug' => $this->slug,
      'genre' => $this->genre,
      'video_url' => $this->video_url,
      'published_at' => $this->published_at?->toDateString(),
      'thumbnail_url' => $thumbnail?->url,
      'artist' => new ArtistCompactResource($this->whenLoaded('artist')),
    ];
  }
}
