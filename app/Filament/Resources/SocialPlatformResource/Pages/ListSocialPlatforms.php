<?php

namespace App\Filament\Resources\SocialPlatformResource\Pages;

use App\Filament\Resources\SocialPlatformResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSocialPlatforms extends ListRecords
{
    protected static string $resource = SocialPlatformResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
