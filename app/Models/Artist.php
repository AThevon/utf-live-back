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
    'social_links',
  ];

  protected $casts = [
    'social_links' => 'array',
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

  public function getPhotoUrlAttribute(): ?string
  {
    return $this->image?->url;
  }
}
