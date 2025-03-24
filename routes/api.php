<?php

use App\Http\Controllers\Api\ArtistController;

Route::get('/artists', [ArtistController::class, 'index']);
Route::get('/artists/{artist:slug}', [ArtistController::class, 'show']);
