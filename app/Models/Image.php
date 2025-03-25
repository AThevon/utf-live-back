<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Image extends Model
{
  protected $fillable = ['path', 'alt'];

  public static function booted(): void
  {
    static::saving(function (Image $image) {
      if ($image->is_profile) {
        static::where('imageable_id', $image->imageable_id)
          ->where('imageable_type', $image->imageable_type)
          ->where('id', '!=', $image->id)
          ->update(['is_profile' => false]);
      }

      if ($image->is_thumbnail) {
        static::where('imageable_id', $image->imageable_id)
          ->where('imageable_type', $image->imageable_type)
          ->where('id', '!=', $image->id)
          ->update(['is_thumbnail' => false]);
      }
    });
  }

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
