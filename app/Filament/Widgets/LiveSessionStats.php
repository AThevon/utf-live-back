<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Artist;

class LiveSessionStats extends BaseWidget
{ 
  protected function getStats(): array
  {
    return [
      Stat::make('Live Sessions', Artist::count())
      ->description('Total live sessions enregistrés')
      ->color('primary')
      ->icon('heroicon-o-video-camera'),
    ];
  }

  protected function getColumns(): int
  {
    return 1;
  }
  
  public function getColumnSpan(): int|string|array
  {
    return 1; 
  }
}
