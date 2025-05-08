<?php

namespace App\Filament\Resources\SaleResource\Pages;

use App\Filament\Resources\SaleResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\ListRecords\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListSales extends ListRecords
{
    protected static string $resource = SaleResource::class;

    protected function getHeaderActions(): array
    {
        return [
        ];
    }

    public function getTabs(): array
    {
        return [
            'today' => Tab::make('Today')
                ->modifyQueryUsing(fn (Builder $query) => $query->whereDate('created_at', today()))
                ->badge(static::getModel()::whereDate('created_at', today())->count())
                ->badgeColor('success'),

            'this_week' => Tab::make('This Week')
                ->modifyQueryUsing(fn (Builder $query) => $query->whereBetween('created_at', [
                    now()->startOfWeek(),
                    now()->endOfWeek(),
                ]))
                ->badge(static::getModel()::whereBetween('created_at', [
                    now()->startOfWeek(),
                    now()->endOfWeek(),
                ])->count()),

            'this_month' => Tab::make('This Month')
                ->modifyQueryUsing(fn (Builder $query) => $query->whereMonth('created_at', now()->month))
                ->badge(static::getModel()::whereMonth('created_at', now()->month)->count()),

            'all' => Tab::make('All Sales')
                ->modifyQueryUsing(fn (Builder $query) => $query)
                ->badge(static::getModel()::count()),
        ];
    }
}
