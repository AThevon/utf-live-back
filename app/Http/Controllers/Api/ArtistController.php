<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ArtistResource;
use App\Http\Resources\ArtistListResource;
use App\Models\Artist;

class ArtistController extends Controller
{
  public function index()
  {
    return ArtistListResource::collection(
      Artist::whereHas('liveSessionsAsMain')
        ->with(['images'])
        ->orderByDesc('created_at')
        ->get()
    );
  }
  
  public function show(Artist $artist)
  {
    $artist->load(['images', 'socialLinks.platform']);
    return new ArtistResource($artist);
  }
}
