<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PlatformResource extends JsonResource
{
  public function toArray(Request $request): array
  {
    return [
      'name' => $this->platform->name,
      'slug' => $this->platform->slug,
      'icon_url' => asset('storage/' . ltrim($this->platform->icon, '/')),
      'url' => $this->url,
    ];
  }
}