<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArtistSocialLink extends Model
{
  protected $fillable = ['artist_id', 'social_platform_id', 'url'];

  public function artist()
  {
    return $this->belongsTo(Artist::class);
  }

  public function platform()
  {
    return $this->belongsTo(SocialPlatform::class, 'social_platform_id');
  }
}

