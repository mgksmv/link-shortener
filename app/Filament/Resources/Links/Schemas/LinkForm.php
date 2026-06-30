<?php

namespace App\Filament\Resources\Links\Schemas;

use App\Models\Link;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class LinkForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns()
            ->components([
                TextInput::make('original_url')
                    ->url()
                    ->required()
                    ->label('Оригинальный URL')
                    ->maxLength(2048)
                    ->placeholder('https://example.com'),
                TextInput::make('code')
                    ->label('Код')
                    ->disabled()
                    ->dehydrated(false)
                    ->formatStateUsing(fn (?string $state): ?string => $state ? url($state) : null)
                    ->copyable()
                    ->hidden(fn (?Link $record) => $record === null),
                TextEntry::make('visits_count')
                    ->label('Общее количество кликов')
                    ->state(fn (Link $record): string => (string) $record->visits()->count())
                    ->hidden(fn (?Link $record) => $record === null),
            ]);
    }
}
