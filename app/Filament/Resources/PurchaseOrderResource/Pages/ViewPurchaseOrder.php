<?php

namespace App\Filament\Resources\PurchaseOrderResource\Pages;

use App\Filament\Resources\PurchaseOrderResource;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists;
use Filament\Infolists\Infolist;

class ViewPurchaseOrder extends ViewRecord
{
    protected static string $resource = PurchaseOrderResource::class;

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Purchase Order Details')
                    ->schema([
                        Infolists\Components\Grid::make(3)
                            ->schema([
                                Infolists\Components\TextEntry::make('po_number')
                                    ->label('PO Number'),
                                Infolists\Components\TextEntry::make('date')
                                    ->date(),
                                Infolists\Components\TextEntry::make('status')
                                    ->badge()
                                    ->color(fn (string $state): string => match ($state) {
                                        'draft' => 'warning',
                                        'pending' => 'primary',
                                        'partially_received' => 'info',
                                        'completed' => 'success',
                                        default => 'gray',
                                    }),
                            ]),
                        Infolists\Components\Grid::make(2)
                            ->schema([
                                Infolists\Components\TextEntry::make('supplier.name')
                                    ->label('Supplier'),
                                Infolists\Components\TextEntry::make('total_amount')
                                    ->money('PHP'),
                            ]),
                        Infolists\Components\TextEntry::make('notes')
                            ->columnSpanFull(),
                    ]),
                Infolists\Components\Section::make('Items')
                    ->schema([
                        Infolists\Components\RepeatableEntry::make('items')
                            ->schema([
                                Infolists\Components\TextEntry::make('product.name')
                                    ->label('Product'),
                                Infolists\Components\TextEntry::make('quantity')
                                    ->label('Ordered Qty'),
                                Infolists\Components\TextEntry::make('received_quantity')
                                    ->label('Received Qty'),
                                Infolists\Components\TextEntry::make('unit_price')
                                    ->money('PHP'),
                                Infolists\Components\TextEntry::make('subtotal')
                                    ->money('PHP'),
                            ])
                            ->columns(5),
                    ]),
            ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\Action::make('print')
                ->label('Print PO')
                ->icon('heroicon-o-printer')
                ->url(fn () => route('purchase-orders.print', ['purchaseOrder' => $this->record]))
                ->openUrlInNewTab(),
        ];
    }
}