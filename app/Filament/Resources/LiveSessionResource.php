<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LiveSessionResource\Pages;
use App\Filament\Resources\LiveSessionResource\RelationManagers;
use App\Models\LiveSession;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Grid;
use Illuminate\Support\Str;
use App\Models\Artist;

class LiveSessionResource extends Resource
{
  protected static ?string $model = LiveSession::class;

  protected static ?string $navigationIcon = 'heroicon-o-video-camera';
  protected static ?string $navigationLabel = 'Live Sessions';
  protected static ?string $navigationGroup = 'Contenu';

  public static function getNavigationBadge(): ?string
  {
    return static::getModel()::count();
  }

  public static function form(Form $form): Form
  {
    return $form->schema([
      Grid::make(12)->schema([
        TextInput::make('title')
          ->label('Titre')
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

        Forms\Components\Toggle::make('auto_generate_slug')
          ->label('Auto')
          ->default(true)
          ->live()
          ->inline(false)
          ->columnSpan(1),

        Select::make('artist_id')
          ->label('Artiste')
          ->options(
            Artist::all()->mapWithKeys(fn($artist) => [
              $artist->id => '
                <div class="flex items-center gap-2">
                  ' . ($artist->profileImage()?->url
                ? '<img src="' . $artist->profileImage()->url . '" class="w-5 h-5 rounded-full object-cover" alt="' . $artist->name . '" />'
                : '<div class="w-5 h-5 rounded-full bg-gray-300"></div>'
              ) . '
                  <span>' . $artist->name . '</span>
                </div>',
            ])
          )
          ->searchable()
          ->preload()
          ->allowHtml()
          ->required()
          ->placeholder('Choisir un artiste')
          ->searchLabels(false)
          ->columnSpan(6),

        Select::make('participants')
          ->label('Participants')
          ->relationship('participants', 'name')
          ->multiple()
          ->searchable()
          ->preload()
          ->options(function (callable $get) {
            $mainArtistId = $get('artist_id');

            return Artist::when($mainArtistId, fn($q) => $q->where('id', '!=', $mainArtistId))
              ->get()
              ->mapWithKeys(fn($artist) => [
                $artist->id => '
                  <div class="flex items-center gap-2">
                    ' . ($artist->profileImage()?->url
                  ? '<img src="' . $artist->profileImage()->url . '" class="w-5 h-5 rounded-full object-cover" alt="' . $artist->name . '" />'
                  : '<div class="w-5 h-5 rounded-full bg-gray-300"></div>'
                ) . '
                    <span>' . $artist->name . '</span>
                  </div>',
              ]);
          })
          ->allowHtml()
          ->placeholder('Choisir un ou des participant(s)')
          ->columnSpan(6),


        TextInput::make('video_url')
          ->label('URL de la vidéo')
          ->required()
          ->url()
          ->columnSpan(6),

        DatePicker::make('published_at')
          ->label('Date de publication')
          ->columnSpan(6),

        Textarea::make('description')
          ->label('Description')
          ->rows(5)
          ->nullable()
          ->columnSpan(12),
      ]),
    ]);
  }

  public static function table(Table $table): Table
  {
    return $table
      ->columns([
        Tables\Columns\TextColumn::make('title')->label('Titre')->sortable()->searchable(),
        Tables\Columns\TextColumn::make('artist.name')->label('Artiste')->sortable()->searchable(),
        Tables\Columns\TextColumn::make('published_at')->date()->label('Publiée le')->sortable(),
      ])
      ->actions([
        Tables\Actions\EditAction::make(),
      ])
      ->bulkActions([
        Tables\Actions\DeleteBulkAction::make(),
      ]);
  }

  public static function getRelations(): array
  {
    return [
      //
    ];
  }

  public static function getPages(): array
  {
    return [
      'index' => Pages\ListLiveSessions::route('/'),
      'create' => Pages\CreateLiveSession::route('/create'),
      'edit' => Pages\EditLiveSession::route('/{record}/edit'),
    ];
  }

}
