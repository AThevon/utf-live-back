<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SocialPlatform extends Model
{
  protected $fillable = ['name', 'slug', 'icon'];

  public function artistLinks(): HasMany
  {
    return $this->hasMany(ArtistSocialLink::class);
  }

  public function getIconUrlAttribute(): string
  {
    return asset($this->icon);
  }
}
