<?php

namespace App\Filament\Resources\PostResource\Pages;

use Filament\Actions;
use Filament\Resources\Components\Tab;
use App\Filament\Resources\PostResource;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListPosts extends ListRecords
{
    protected static string $resource = PostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make(),
            'published' => Tab::make()->modifyQueryUsing(function (Builder $query) {
                $query->where('published', true);
            }),
            'unpublished' => Tab::make()->modifyQueryUsing(function (Builder $query) {
                $query->where('published', false);
            }),
        ];
    }
}