<?php

namespace App\Filament\Resources\ArtistResource\Pages;

use App\Filament\Resources\ArtistResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Livewire\TemporaryUploadedFile;

class CreateArtist extends CreateRecord
{
  protected static string $resource = ArtistResource::class;

}
