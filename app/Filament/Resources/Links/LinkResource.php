<?php

namespace App\Filament\Resources\Links;

use App\Filament\Resources\Links\RelationManagers\VisitsRelationManager;
use App\Filament\Resources\Links\Pages\EditLink;
use App\Filament\Resources\Links\Pages\ListLinks;
use App\Filament\Resources\Links\Schemas\LinkForm;
use App\Filament\Resources\Links\Tables\LinksTable;
use App\Models\Link;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class LinkResource extends Resource
{
    public const string TITLE = 'Ссылки';

    protected static ?string $model = Link::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedLink;

    protected static ?string $navigationLabel = self::TITLE;

    protected static ?string $breadcrumb = self::TITLE;

    protected static ?string $modelLabel = 'Ссылка';

    protected static ?string $label = 'ссылка';

    protected static ?string $pluralLabel = 'ссылок';

    public static function form(Schema $schema): Schema
    {
        return LinkForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LinksTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            VisitsRelationManager::class,
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('user_id', auth()->id())
            ->withCount('visits');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListLinks::route('/'),
            'edit' => EditLink::route('/{record}/edit'),
        ];
    }
}
