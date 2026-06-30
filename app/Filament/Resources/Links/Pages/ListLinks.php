<?php

namespace App\Filament\Resources\Links\Pages;

use App\Filament\Resources\Links\LinkResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Contracts\Support\Htmlable;

class ListLinks extends ListRecords
{
    protected static string $resource = LinkResource::class;

    public function getTitle(): string|Htmlable
    {
        return 'Ссылки';
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->modal()
                ->createAnother(false)
                ->mutateDataUsing(function (array $data): array {
                    $data['user_id'] = auth()->id();
                    return $data;
                }),
        ];
    }
}
