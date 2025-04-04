<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ArtistResource extends JsonResource
{
  public function toArray(Request $request): array
  {
    $profileImage = $this->images->firstWhere('is_profile', true);
    $thumbnailImage = $this->images->firstWhere('is_thumbnail', true);

    return [
      'id' => $this->id,
      'name' => $this->name,
      'slug' => $this->slug,
      'bio' => $this->bio,
      'profile_image' => $profileImage?->url,
      'thumbnail_image' => $thumbnailImage?->url,

      'images' => $this->images->map(function ($image) {
        $data = [
          'id' => $image->id,
          'url' => $image->url,
          'alt' => $image->alt,
        ];

        if ($image->is_profile) {
          $data['is_profile'] = true;
        }

        if ($image->is_thumbnail) {
          $data['is_thumbnail'] = true;
        }

        return $data;
      }),

      'platforms' => $this->whenLoaded('platformLinks', function () {
        $social = $this->platformLinks
          ->filter(fn($link) => $link->platform?->type === 'social');

        $music = $this->platformLinks
          ->filter(fn($link) => $link->platform?->type === 'music');

        return [
          'social' => PlatformResource::collection($social),
          'music' => PlatformResource::collection($music),
        ];
      }),

      'latest_session_slug' => optional($this->liveSessionsAsMain->sortByDesc('created_at')->first())->slug,

      'created_at' => $this->created_at->toDateTimeString(),
      'updated_at' => $this->updated_at->toDateTimeString(),
    ];
  }
}
