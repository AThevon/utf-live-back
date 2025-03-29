<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\LiveSessionResource;
use App\Http\Resources\LiveSessionListResource;
use App\Models\LiveSession;

class LiveSessionController extends Controller
{
  public function index()
  {
    return LiveSessionListResource::collection(
      LiveSession::with('artist.images')
        ->orderByDesc('published_at')
        ->get()
    );
  }

  public function show($slug)
  {
    $session = LiveSession::where('slug', $slug)
      ->with(['artist.images', 'artist.socialLinks.platform', 'participants.images', 'participants.socialLinks.platform'])
      ->firstOrFail();

    return new LiveSessionResource($session);
  }

  public function latest()
  {
    $sessions = LiveSession::with([
      'artist.images',
    ])
      ->latest('published_at')
      ->take(3)
      ->get();

    return LiveSessionListResource::collection($sessions);
  }
}
