<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\ListRecords\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListProducts extends ListRecords
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All Products')
                ->badge(static::getModel()::count()),
            
            'active' => Tab::make('Active')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('is_active', true))
                ->badge(static::getModel()::where('is_active', true)->count()),
            
            'inactive' => Tab::make('Inactive')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('is_active', false))
                ->badge(static::getModel()::where('is_active', false)->count()),
            
            'low_stock' => Tab::make('Low Stock')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('stock', '<=', config('pos.inventory.low_stock_threshold')))
                ->badge(static::getModel()::where('stock', '<=', config('pos.inventory.low_stock_threshold'))->count())
                ->badgeColor('danger'),
        ];
    }
}