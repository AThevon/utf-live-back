<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LiveSession extends Model
{
  protected $fillable = [
    'title',
    'slug',
    'artist_id',
    'video_url',
    'published_at',
    'description',
  ];

  protected $casts = [
    'published_at' => 'date',
];

  public function artist()
  {
    return $this->belongsTo(Artist::class);
  }

  public function participants()
  {
    return $this->belongsToMany(Artist::class, 'artist_live_session_participant');
  }

}
