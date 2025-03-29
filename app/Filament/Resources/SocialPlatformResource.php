<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SocialPlatformResource\Pages;
use App\Models\SocialPlatform;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;

class SocialPlatformResource extends Resource
{
  protected static ?string $model = SocialPlatform::class;

  protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
  protected static ?string $navigationLabel = 'Réseaux sociaux';
  protected static ?string $navigationGroup = 'Contenu';

  public static function form(Form $form): Form
  {
    return $form->schema([
      TextInput::make('name')
        ->label('Nom du réseau')
        ->required()
        ->maxLength(50)
        ->live(onBlur: true)
        ->afterStateUpdated(function (string $state, callable $set) {
          $set('slug', \Str::slug($state));
        }),

      TextInput::make('slug')
        ->label('Slug')
        ->disabled()
        ->dehydrated(),

      FileUpload::make('icon')
        ->label("Icône")
        ->helperText('PNG, JPG, SVG (privilégier SVG)')
        ->image()
        ->imageEditor()
        ->directory('icons/social')
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

      ])
      ->actions([
        Tables\Actions\EditAction::make()->label('Modifier'),
      ])
      ->bulkActions([
        Tables\Actions\DeleteBulkAction::make()->label('Supprimer sélection'),
      ]);
  }

  public static function getRelations(): array
  {
    return [];
  }

  public static function getPages(): array
  {
    return [
      'index' => Pages\ListSocialPlatforms::route('/'),
      'create' => Pages\CreateSocialPlatform::route('/create'),
      'edit' => Pages\EditSocialPlatform::route('/{record}/edit'),
    ];
  }
}
