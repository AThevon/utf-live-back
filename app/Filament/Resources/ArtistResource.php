<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ArtistResource\Pages\CreateArtist;
use App\Filament\Resources\ArtistResource\Pages\EditArtist;
use App\Filament\Resources\ArtistResource\Pages\ListArtists;
use App\Filament\Resources\ArtistResource\RelationManagers\ImageRelationManager;
use App\Filament\Resources\ArtistResource\RelationManagers\SocialLinksRelationManager;
use App\Models\Artist;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Grid;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Illuminate\Support\Str;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\BulkActionGroup;


class ArtistResource extends Resource
{
  protected static ?string $model = Artist::class;

  protected static ?string $navigationIcon = 'heroicon-o-user-group';

  protected static ?string $navigationLabel = 'Artistes';

  protected static ?string $navigationGroup = 'Contenu';

  public static function getNavigationBadge(): ?string
  {
    return static::getModel()::count();
  }

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

          Textarea::make('bio')
            ->rows(8)
            ->nullable()
            ->label('Biographie')
            ->columnSpan(12),
        ]),
    ]);
  }

  public static function table(Table $table): Table
  {
    return $table
      ->columns([
        ImageColumn::make('image_url')
        ->label(' ')
        ->getStateUsing(fn(Artist $record) => $record->profileImage()?->url)
          ->circular()
          ->size(40)
          ->defaultImageUrl('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6"><path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /></svg>'),

        TextColumn::make('name')->label('Nom')->sortable()->searchable(),

        TextColumn::make('created_at')
          ->label('Ajoutée le')
          ->dateTime()
          ->sortable()
          ->toggleable(isToggledHiddenByDefault: false),

        TextColumn::make('updated_at')
          ->label('Modifiée le')
          ->dateTime()
          ->sortable()
          ->toggleable(isToggledHiddenByDefault: true),
      ])
      ->filters([
        //
      ])
      ->actions([
        EditAction::make(),
      ])
      ->bulkActions([
        BulkActionGroup::make([
          DeleteBulkAction::make(),
        ]),
      ]);
  }

  public static function getRelations(): array
  {
    return [
      ImageRelationManager::class,
      SocialLinksRelationManager::class,
    ];
  }

  public static function getPages(): array
  {
    return [
      'index' => ListArtists::route('/'),
      'create' => CreateArtist::route('/create'),
      'edit' => EditArtist::route('/{record}/edit'),
    ];
  }
}
