<?php

namespace App\Filament\Resources\ArtistResource\RelationManagers;

use Intervention\Image\ImageManager;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Filament\Forms;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\FileUpload;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Intervention\Image\Encoders\WebpEncoder;

class ImageRelationManager extends RelationManager
{
  protected static string $relationship = 'images';
  protected static ?string $title = 'Images';

  public function form(Forms\Form $form): Forms\Form
  {
    return $form->schema([
      FileUpload::make('path')
        ->label('Image')
        ->disk('local') // temporaire
        ->directory('tmp')
        ->visibility('private')
        ->preserveFilenames()
        ->image()
        ->imageEditor()
        ->required()
        ->maxSize(100 * 1024),
    ]);
  }

  public function table(Table $table): Table
  {
    return $table
      ->recordTitleAttribute('alt')
      ->columns([
        ImageColumn::make('path')
          ->label('Image')
          ->size(200)
          ->url(fn($record) => Storage::disk('s3')->url($record->path))
          ->extraImgAttributes([
            'style' => 'object-fit: contain;',
          ]),

        TextColumn::make('created_at')->dateTime('d/m/Y H:i')->label('Ajoutée le')->sortable(true),
        ToggleColumn::make('is_profile')->label('Profil'),
        ToggleColumn::make('is_thumbnail')->label('Vignette'),
      ])
      ->headerActions([
        CreateAction::make()
          ->label('Uploader plusieurs images')
          ->form([
            FileUpload::make('paths')
              ->label('Images')
              ->disk('local')
              ->directory('tmp')
              ->visibility('private')
              ->preserveFilenames()
              ->multiple()
              ->image()
              ->imageEditor()
              ->required()
              ->dehydrated(true),
          ])
          ->action(function (array $data, $livewire) {
            $paths = $data['paths'] ?? [];

            if (empty($paths))
              return;

            $manager = new ImageManager(new \Intervention\Image\Drivers\Gd\Driver());
            $artist = $livewire->getOwnerRecord();
            $artistName = $artist->name ?? 'Image';

            foreach ($paths as $path) {
              $localPath = storage_path('app/private/' . $path);
              $filename = 'artists/' . Str::slug($artistName) . '/' . Str::uuid() . '.webp';

              $image = $manager->read($localPath);
              if ($image->height() > 1080) {
                $image = $image->scaleDown(height: 1080);
              }

              $encoded = $image->encode(new WebpEncoder(quality: 90));

              Storage::disk('s3')->put($filename, (string) $encoded, [
                'visibility' => 'private',
                'ContentType' => 'image/webp',
              ]);

              Storage::disk('local')->delete($path);

              $artist->images()->create([
                'path' => $filename,
                'alt' => $artistName,
              ]);
            }
          }),
      ])
      ->actions([
        DeleteAction::make(),
      ]);
  }
}
