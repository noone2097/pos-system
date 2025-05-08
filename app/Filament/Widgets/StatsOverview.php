<?php

namespace App\Filament\Widgets;

use App\Models\Sale;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Log;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $isAdmin = auth()->user()->hasRole('admin');
        $username = auth()->user()->name;
        
        Log::info("StatsOverview Widget Loaded", [
            'user' => $username,
            'is_admin' => $isAdmin,
        ]);
        
        return [
            Stat::make('Today\'s Sales', 
                '₱' . number_format(Sale::whereDate('created_at', today())->sum('total'), 2))
                ->description('Total sales for today')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chart([7, 4, 6, 8, 5, 3, 8])
                ->color('success'),

            Stat::make('Today\'s Sale Transactions', 
                Sale::whereDate('created_at', today())->count())
                ->description('Number of sale transactions today')
                ->descriptionIcon('heroicon-m-shopping-cart')
                ->chart([3, 5, 7, 4, 6, 5, 8])
                ->color('success'),

            Stat::make('Average Sale', function () {
                $avg = Sale::whereDate('created_at', today())->avg('total') ?? 0;
                return '₱' . number_format($avg, 2);
            })
                ->description('Average sale amount today')
                ->descriptionIcon('heroicon-m-calculator')
                ->chart([4, 6, 5, 8, 3, 7, 5])
                ->color('success'),
        ];
    }
}
