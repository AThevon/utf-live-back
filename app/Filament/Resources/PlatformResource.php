<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PlatformResource\Pages;
use App\Models\Platform;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Select;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;




class PlatformResource extends Resource
{
  protected static ?string $model = Platform::class;

  protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
  protected static ?string $navigationLabel = 'Plateformes';
  protected static ?string $navigationGroup = 'Contenu';

  public static function form(Form $form): Form
  {
    return $form->schema([
      TextInput::make('name')
        ->label('Nom de la plateforme')
        ->required()
        ->maxLength(50)
        ->live(onBlur: true)
        ->afterStateUpdated(fn($state, $set) => $set('slug', \Str::slug($state ?? ''))),

      TextInput::make('slug')
        ->label('Slug')
        ->disabled()
        ->dehydrated(),

      Select::make('type')
        ->label('Type de plateforme')
        ->options([
          'social' => '🌐 Réseau social',
          'music' => '🎵 Plateforme musicale',
        ])
        ->native(false) // pour une UI plus clean
        ->searchable(false)
        ->required()
        ->default('social')
      ,

      FileUpload::make('icon')
        ->label("Icône")
        ->helperText('SVG uniquement')
        ->image()
        ->imageEditor()
        ->directory('icons/platforms')
        ->disk('public')
        ->visibility('public')
        ->preserveFilenames()
        ->imagePreviewHeight('100')
        ->loadingIndicatorPosition('left')
        ->panelLayout('compact')
        ->required(),
    ]);
  }


  public static function table(Table $table): Table
  {
    return $table
      ->columns([
        ImageColumn::make('icon')
          ->label('Icône')
          ->disk('public')
          ->size(40),

        TextColumn::make('name')
          ->label('Nom')
          ->searchable()
          ->sortable(),

        TextColumn::make('type')
          ->label('Type')
          ->formatStateUsing(fn(string $state) => $state === 'music' ? '🎵 Musique' : '🌐 Social')
          ->badge()
          ->color(fn(string $state) => $state === 'music' ? 'success' : 'primary'),
      ])
      ->filters([
        SelectFilter::make('type')
          ->label('Type')
          ->options([
            'social' => 'Réseau social',
            'music' => 'Plateforme musicale',
          ]),
      ], layout: FiltersLayout::AboveContent)
      ->actions([
        EditAction::make()->label('Modifier'),
      ])
      ->bulkActions([
        DeleteBulkAction::make()->label('Supprimer sélection'),
      ]);
  }


  public static function getRelations(): array
  {
    return [];
  }

  public static function getPages(): array
  {
    return [
      'index' => Pages\ListPlatforms::route('/'),
      'create' => Pages\CreatePlatform::route('/create'),
      'edit' => Pages\EditPlatform::route('/{record}/edit'),
    ];
  }
}
