<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LiveSessionResource extends JsonResource
{
  public function toArray(Request $request): array
  {
    return [
      'id' => $this->id,
      'title' => $this->title,
      'slug' => $this->slug,
      'video_url' => $this->video_url,
      'description' => $this->description,
      'published_at' => $this->published_at?->toDateString(),

      'artist' => new ArtistResource($this->whenLoaded('artist')),

      'participants' => ArtistResource::collection($this->whenLoaded('participants')),

      'created_at' => $this->created_at->toDateTimeString(),
      'updated_at' => $this->updated_at->toDateTimeString(),
    ];
  }
}
