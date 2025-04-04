<?php

namespace App\Filament\Resources\ArtistResource\Pages;

use App\Filament\Resources\ArtistResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditArtist extends EditRecord
{
  protected static string $resource = ArtistResource::class;

  protected function getHeaderActions(): array
  {
    return [
      Actions\DeleteAction::make(),
    ];
  }

}
