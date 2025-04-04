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

class MusicalLinksRelationManager extends RelationManager
{
    protected static string $relationship = 'musicalLinks';
    protected static ?string $title = 'Plateformes musicales';

    public function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([
            Select::make('platform_id')
                ->label('Plateforme musicale')
                ->relationship(
                    'platform',
                    'name',
                    fn($query) => $query->where('type', 'music')
                )
                ->searchable()
                ->preload()
                ->getOptionLabelFromRecordUsing(function ($record) {
                    $url = asset('storage/' . ltrim($record->icon, '/'));
                    return "
                        <div class='flex items-center gap-2'>
                            <img src='{$url}' alt='{$record->name}' class='w-5 h-5 object-contain' />
                            {$record->name}
                        </div>
                    ";
                })
                ->allowHtml()
                ->required()
                ->placeholder('Choisir une plateforme musicale'),

            TextInput::make('url')
                ->label('Lien embed')
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
                    ->getStateUsing(fn($record) => asset('storage/' . ltrim($record->platform->icon, '/')))
                    ->width(24)
                    ->height(24),

                Tables\Columns\TextColumn::make('platform.name')
                    ->label('Plateforme'),

                Tables\Columns\TextColumn::make('url')
                    ->label('Lien embed')
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
