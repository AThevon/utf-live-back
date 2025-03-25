<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Artist extends Model
{
  use HasFactory;

  protected $fillable = [
    'name',
    'slug',
    'bio',
  ];

  /**
   * Relation avec les live sessions.
   */
  public function liveSessions(): HasMany
  {
    return $this->hasMany(LiveSession::class);
  }


  public function images(): MorphMany
  {
    return $this->morphMany(\App\Models\Image::class, 'imageable');
  }

  public function socialLinks(): HasMany
  {
    return $this->hasMany(ArtistSocialLink::class);
  }


  public function getPhotoUrlAttribute(): ?string
  {
    return $this->image?->url;
  }

  public function getRouteKeyName(): string
  {
    return 'slug';
  }

  public function profileImage(): ?Image
  {
    return $this->images()->where('is_profile', true)->first();
  }

  public function thumbnailImage(): ?Image
  {
    return $this->images()->where('is_thumbnail', true)->first();
  }
}
