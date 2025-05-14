<?php

namespace App\Filament\Resources\PurchaseOrderResource\Pages;

use App\Filament\Resources\PurchaseOrderResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\ListRecords\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListPurchaseOrders extends ListRecords
{
    protected static string $resource = PurchaseOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->visible(fn (): bool => auth()->user()->can('create purchase orders')),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All Orders')
                ->badge(static::getModel()::count()),

            'draft' => Tab::make('Draft')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'draft'))
                ->badge(static::getModel()::where('status', 'draft')->count())
                ->badgeColor('warning'),

            'pending' => Tab::make('Pending')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'pending'))
                ->badge(static::getModel()::where('status', 'pending')->count())
                ->badgeColor('primary'),

            'partially_received' => Tab::make('Partially Received')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'partially_received'))
                ->badge(static::getModel()::where('status', 'partially_received')->count())
                ->badgeColor('info'),

            'completed' => Tab::make('Completed')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'completed'))
                ->badge(static::getModel()::where('status', 'completed')->count())
                ->badgeColor('success'),
        ];
    }
}