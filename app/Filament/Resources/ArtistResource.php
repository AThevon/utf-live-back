<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ArtistResource\Pages;
use App\Filament\Resources\ArtistResource\RelationManagers;
use App\Models\Artist;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Grid;
use Illuminate\Support\Str;
use App\Filament\Resources\ArtistResource\RelationManagers\ImageRelationManager;


class ArtistResource extends Resource
{
  protected static ?string $model = Artist::class;

  protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

  public static function form(Form $form): Form
  {
    return $form->schema([

      Grid::make(12)
        ->schema([
          TextInput::make('name')
            ->label('Nom')
            ->required()
            ->live(onBlur: true)
            ->afterStateUpdated(function ($state, callable $set, $get) {
              if ($get('auto_generate_slug')) {
                $set('slug', Str::slug($state));
              }
            })
            ->columnSpan(6),

          TextInput::make('slug')
            ->label('Slug')
            ->required()
            ->unique(ignoreRecord: true)
            ->readOnly(fn(callable $get) => $get('auto_generate_slug'))
            ->dehydrated()
            ->columnSpan(5),

          Toggle::make('auto_generate_slug')
            ->label('Auto')
            ->default(true)
            ->live()
            ->inline(false)
            ->columnSpan(1),
        ]),

      Textarea::make('bio')->rows(4)->nullable()->label('Biographie'),

      KeyValue::make('social_links')
        ->label('Réseaux sociaux')
        ->nullable()
        ->dehydrated(fn($state) => filled($state))
        ->default([]),
    ]);
  }

  public static function table(Table $table): Table
  {
    return $table
      ->columns([
        Tables\Columns\TextColumn::make('name')
          ->searchable(),
        Tables\Columns\TextColumn::make('slug')
          ->searchable(),
        Tables\Columns\TextColumn::make('created_at')
          ->dateTime()
          ->sortable()
          ->toggleable(isToggledHiddenByDefault: true),
        Tables\Columns\TextColumn::make('updated_at')
          ->dateTime()
          ->sortable()
          ->toggleable(isToggledHiddenByDefault: true),
      ])
      ->filters([
        //
      ])
      ->actions([
        Tables\Actions\EditAction::make(),
      ])
      ->bulkActions([
        Tables\Actions\BulkActionGroup::make([
          Tables\Actions\DeleteBulkAction::make(),
        ]),
      ]);
  }

  public static function getRelations(): array
  {
    return [
      ImageRelationManager::class,
    ];
  }

  public static function getPages(): array
  {
    return [
      'index' => Pages\ListArtists::route('/'),
      'create' => Pages\CreateArtist::route('/create'),
      'edit' => Pages\EditArtist::route('/{record}/edit'),
    ];
  }
}
