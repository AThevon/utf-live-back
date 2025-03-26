<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ArtistListResource extends JsonResource
{
  public function toArray(Request $request): array
  {
    $profileImage = $this->images->firstWhere('is_profile', true);

    return [
      'id' => $this->id,
      'name' => $this->name,
      'slug' => $this->slug,
      'bio' => $this->bio,
      'profile_image' => $profileImage?->url,
    ];
  }
}
