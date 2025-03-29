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
use Filament\Forms\Components\Actions;
use Filament\Notifications\Notification;
use App\Services\YoutubeService;
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
          ->relationship('artist', 'name')
          ->searchable()
          ->preload()
          ->getOptionLabelFromRecordUsing(function ($record) {
            return '
                  <div class="flex items-center gap-2">
                      ' . ($record->profileImage()?->url
              ? '<img src="' . $record->profileImage()->url . '" class="w-5 h-5 rounded-full object-cover" />'
              : '<div class="w-5 h-5 rounded-full bg-gray-300"></div>') . '
                      <span>' . $record->name . '</span>
                  </div>';
          })
          ->allowHtml()
          ->required()
          ->placeholder('Choisir un artiste')
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


        Grid::make(12)->schema([
          TextInput::make('video_url')
            ->label('URL de la vidéo')
            ->required()
            ->url()
            ->live(onBlur: true)
            ->afterStateUpdated(function ($state, callable $set) {
              // Récupère l'ID via regex
              preg_match('/(?:v=|embed\/)([a-zA-Z0-9_-]{11})/', $state, $matches);

              if (isset($matches[1])) {
                $videoId = $matches[1];
                $set('video_url', 'https://www.youtube.com/embed/' . $videoId);
              }
            })
            ->columnSpan(6),

          TextInput::make('genre')
            ->label('Genre')
            ->required()
            ->placeholder('Genre de la session')
            ->columnSpan(3),

          DatePicker::make('published_at')
            ->label('Date de publication')
            ->required()
            ->columnSpan(3),
        ]),


        Textarea::make('description')
          ->label('Description')
          ->rows(10)
          ->nullable()
          ->columnSpan(12),

        Actions::make([
          Actions\Action::make('fetchYoutubeDescription')
            ->label('Importer depuis YouTube')
            ->icon('heroicon-o-arrow-down-tray')
            ->size('lg')
            ->action(function ($livewire, $data) {
              $videoUrl = $livewire->form->getState()['video_url'] ?? null;

              if (!$videoUrl) {
                Notification::make()
                  ->title('URL manquante')
                  ->body("Tu dois d'abord renseigner l'URL de la vidéo.")
                  ->danger()
                  ->send();
                return;
              }

              $videoId = Str::between($videoUrl, 'embed/', '?') ?: Str::after($videoUrl, 'embed/');
              $snippet = app(abstract: YoutubeService::class)->fetchSnippet($videoId);

              if (!$snippet || empty($snippet['description'])) {
                Notification::make()
                  ->title("Échec")
                  ->body("Impossible de récupérer la description.")
                  ->danger()
                  ->send();
                return;
              }

              $livewire->form->fill([
                ...$livewire->form->getState(),
                'description' => $snippet['description'],
              ]);

              Notification::make()
                ->title('Description importée')
                ->success()
                ->send();
            }),
        ])
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
