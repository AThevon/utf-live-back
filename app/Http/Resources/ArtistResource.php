<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ArtistResource extends JsonResource
{
  public function toArray(Request $request): array
  {
    return [
      'id' => $this->id,
      'name' => $this->name,
      'slug' => $this->slug,
      'bio' => $this->bio,

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

      'socials' => $this->whenLoaded('socialLinks', function () {
        return $this->socialLinks->map(function ($link) {
          return [
            'name' => $link->platform->name,
            'slug' => $link->platform->slug,
            'icon_url' => $link->platform->icon_url,
            'url' => $link->url,
          ];
        });
      }),

      'created_at' => $this->created_at->toDateTimeString(),
      'updated_at' => $this->updated_at->toDateTimeString(),
    ];
  }
}
