<?php

namespace App\Filament\Resources\Links\Tables;

use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class LinksTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('original_url')
                    ->label('Оригинальный URL')
                    ->searchable()
                    ->limit(50),
                TextColumn::make('code')
                    ->label('Короткая ссылка')
                    ->formatStateUsing(fn (string $state): string => url($state))
                    ->url(fn (string $state): string => url($state), true)
                    ->copyable()
                    ->copyMessage('Ссылка скопирована')
                    ->copyMessageDuration(1500)
                    ->searchable(),
                TextColumn::make('visits_count')
                    ->label('Клики')
                    ->badge()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Дата создания')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ])
            ->recordActions([
                EditAction::make()->iconButton(),
                DeleteAction::make()->iconButton(),
            ]);
    }
}
