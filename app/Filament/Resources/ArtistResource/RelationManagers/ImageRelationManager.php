<?php

namespace App\Filament\Resources\ArtistResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageRelationManager extends RelationManager
{
  protected static string $relationship = 'images';

  protected static ?string $title = 'Images';

  public function form(Forms\Form $form): Forms\Form
  {
    return $form->schema([
      FileUpload::make('path')
        ->label('Image')
        ->disk('s3')
        ->directory('artists')
        ->visibility('private')
        ->preserveFilenames()
        ->image()
        ->imageEditor()
        ->maxSize(10 * 1024)
        ->required()
        ->getUploadedFileNameForStorageUsing(fn($file) => uniqid() . '.' . $file->getClientOriginalExtension()),

      TextInput::make('alt')
        ->label('Titre de l\'image')
        ->required(),
    ]);
  }

  public function table(Table $table): Table
  {
    return $table
      ->recordTitleAttribute('alt')
      ->columns([
        Tables\Columns\ImageColumn::make('path')
          ->label('Image')
          ->size(100)
          ->url(fn($record) => filled($record->path) ? Storage::disk('s3')->url($record->path) : 'https://placehold.co/40x40'),
        Tables\Columns\TextColumn::make('alt'),
        Tables\Columns\TextColumn::make('created_at')->dateTime('d/m/Y H:i'),
      ])
      ->headerActions([
        Tables\Actions\CreateAction::make(),
      ])
      ->actions([
        ViewAction::make(),
        EditAction::make(),
        DeleteAction::make(),
      ]);
  }
}
