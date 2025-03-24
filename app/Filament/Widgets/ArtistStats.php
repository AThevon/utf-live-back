<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Artist;

class ArtistStats extends BaseWidget
{
  protected function getStats(): array
  {
    return [
      Stat::make('Artistes', Artist::count())
        ->description('Total artistes enregistrés')
        ->color('primary')
        ->icon('heroicon-o-users'),
    ];
  }
}
