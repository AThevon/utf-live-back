<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Image extends Model
{
  protected $fillable = ['path', 'alt'];

  public function imageable()
  {
    return $this->morphTo();
  }

  public function getUrlAttribute(): ?string
  {
    return $this->path
      ? Storage::disk('s3')->url($this->path)
      : null;
  }
}
