<?php

namespace App\Filament\Widgets;

use App\Models\Product;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class InventoryStats extends BaseWidget
{
    protected static ?string $pollingInterval = null;

    public static function canView(): bool
    {
        return auth()->check() && auth()->user()->hasRole('admin');
    }

    protected function getStats(): array
    {
        return [
            Stat::make('Low Stock Items',
                Product::where('stock', '<=', config('pos.inventory.low_stock_threshold'))->count())
                ->description('Items below threshold')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color('danger'),

            Stat::make('Out of Stock Items',
                Product::where('stock', '=', 0)->count())
                ->description('Items with zero stock')
                ->descriptionIcon('heroicon-m-x-circle')
                ->color('danger'),

            Stat::make('Total Products',
                Product::where('is_active', true)->count())
                ->description('Active products')
                ->descriptionIcon('heroicon-m-cube')
                ->color('info'),
        ];
    }
}
