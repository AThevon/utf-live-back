<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Platform extends Model
{
  protected $fillable = ['name', 'slug', 'type', 'icon'];

  public function artistLinks(): HasMany
  {
    return $this->hasMany(ArtistPlatformLink::class);
  }

  public function getIconUrlAttribute(): string
  {
    return asset($this->icon);
  }
}
