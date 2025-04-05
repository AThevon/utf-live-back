<?php

use App\Http\Controllers\Api\ArtistController;
use App\Http\Controllers\Api\LiveSessionController;
use App\Http\Controllers\Api\ContactController;

Route::get('/artists', [ArtistController::class, 'index']);
Route::get('/artists/{artist:slug}', [ArtistController::class, 'show']);

Route::get('/live-sessions', [LiveSessionController::class, 'index']);
Route::get('/live-sessions/latest', [LiveSessionController::class, 'latest']);
Route::get('/live-sessions/{slug}', [LiveSessionController::class, 'show']);

Route::post('/contact', [ContactController::class, 'send']);