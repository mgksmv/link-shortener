<?php

namespace App\Filament\Resources\Links\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class VisitsRelationManager extends RelationManager
{
    protected static string $relationship = 'visits';

    protected static ?string $title = 'Статистика переходов';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('visited_at')
            ->defaultSort('visited_at', 'desc')
            ->emptyStateHeading('Переходов пока нет...')
            ->searchable(false)
            ->columnManager(false)
            ->columns([
                TextColumn::make('ip_address')
                    ->label('IP-адрес')
                    ->searchable(),
                TextColumn::make('visited_at')
                    ->label('Дата и время перехода')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ]);
    }
}
