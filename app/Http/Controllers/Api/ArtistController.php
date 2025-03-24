<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ArtistResource;
use App\Models\Artist;

class ArtistController extends Controller
{
  public function index()
  {
    return ArtistResource::collection(
      Artist::with(['images', 'socialLinks.platform'])->get()
    );
  }

  public function show(Artist $artist)
  {
    $artist->load(['images', 'socialLinks.platform']);
    return new ArtistResource($artist);
  }
}
