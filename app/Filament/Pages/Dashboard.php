<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\InventoryStats;
use App\Filament\Widgets\StatsOverview;
use Filament\Pages\Dashboard as BaseDashboard;
use Illuminate\Support\Facades\Log;

class Dashboard extends BaseDashboard
{
    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\Action::make('new_sale')
                ->label('New Sale')
                ->url(fn () => \App\Filament\Resources\SaleResource::getUrl('create'))
                ->visible(fn () => auth()->user()->hasAnyRole(['admin', 'cashier']))
                ->icon('heroicon-m-plus'),
        ];
    }

    public function getWidgets(): array
    {
        Log::info('Loading dashboard widgets');

        return [
            StatsOverview::class,
            InventoryStats::class,
        ];
    }
}
