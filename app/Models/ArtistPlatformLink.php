<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArtistPlatformLink extends Model
{
  protected $fillable = ['artist_id', 'platform_id', 'url'];

  public function artist()
  {
    return $this->belongsTo(Artist::class);
  }

  public function platform()
  {
    return $this->belongsTo(Platform::class, 'platform_id');
  }
}

