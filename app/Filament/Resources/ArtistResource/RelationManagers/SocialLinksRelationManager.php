<?php

namespace App\Filament\Resources\ArtistResource\RelationManagers;

use Filament\Forms;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Support\Enums\IconPosition;

class SocialLinksRelationManager extends RelationManager
{
  protected static string $relationship = 'socialLinks';
  protected static ?string $title = 'Réseaux sociaux';

  public function form(Forms\Form $form): Forms\Form
  {
    return $form->schema([
      Select::make('social_platform_id')
        ->label('Plateforme')
        ->relationship('platform', 'name')
        ->searchable()
        ->preload()
        ->getOptionLabelFromRecordUsing(fn($record) => "
        <div class='flex items-center gap-2'>
          <img src='{$record->icon}' alt='{$record->name}' class='w-5 h-5' />
          {$record->name}
        </div>
      ")
        ->allowHtml()
        ->required()
        ->placeholder('Choisir une plateforme'),



      TextInput::make('url')
        ->label('Lien URL')
        ->required()
        ->url(),
    ]);
  }

  public function table(Table $table): Table
  {
    return $table
      ->columns([
        Tables\Columns\ImageColumn::make('platform.icon_url')
          ->label(' ')
          ->width(24)
          ->height(24),

        Tables\Columns\TextColumn::make('platform.name')
          ->label('Plateforme'),

        Tables\Columns\TextColumn::make('url')
          ->label('Lien')
          ->url(fn($record) => $record->url)
          ->openUrlInNewTab(),
      ])
      ->headerActions([
        Tables\Actions\CreateAction::make(),
      ])
      ->actions([
        EditAction::make(),
        DeleteAction::make(),
      ]);
  }
}
