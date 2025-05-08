<?php

namespace App\Filament\Resources\SaleResource\Pages;

use App\Filament\Resources\SaleResource;
use Filament\Actions;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;

class ViewSale extends ViewRecord
{
    protected static string $resource = SaleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('print')
                ->icon('heroicon-m-printer')
                ->url(fn () => route('sales.print', $this->record))
                ->openUrlInNewTab(),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Sale Information')
                    ->schema([
                        Infolists\Components\TextEntry::make('reference'),
                        Infolists\Components\TextEntry::make('customer.name'),
                        Infolists\Components\TextEntry::make('user.name')
                            ->label('Cashier'),
                        Infolists\Components\TextEntry::make('created_at')
                            ->label('Date')
                            ->dateTime('M j, Y : g:i A'),
                    ])
                    ->columns(2),

                Infolists\Components\Section::make('Items')
                    ->schema([
                        Infolists\Components\RepeatableEntry::make('items')
                            ->schema([
                                Infolists\Components\TextEntry::make('product_name'),
                                Infolists\Components\TextEntry::make('quantity')
                                    ->alignRight(),
                                Infolists\Components\TextEntry::make('unit_price')
                                    ->money('PHP')
                                    ->alignRight(),
                                Infolists\Components\TextEntry::make('subtotal')
                                    ->money('PHP')
                                    ->alignRight(),
                            ])
                            ->columns(4),
                    ]),

                Infolists\Components\Section::make('Payment Information')
                    ->schema([
                        Infolists\Components\TextEntry::make('subtotal')
                            ->money('PHP'),
                        Infolists\Components\TextEntry::make('tax')
                            ->money('PHP'),
                        Infolists\Components\TextEntry::make('discount')
                            ->money('PHP'),
                        Infolists\Components\TextEntry::make('total')
                            ->money('PHP')
                            ->weight('bold'),
                        Infolists\Components\TextEntry::make('payment_method'),
                        Infolists\Components\TextEntry::make('payment_amount')
                            ->money('PHP'),
                        Infolists\Components\TextEntry::make('change')
                            ->money('PHP'),
                        Infolists\Components\TextEntry::make('status')
                            ->badge(),
                    ])
                    ->columns(2),

                Infolists\Components\Section::make('Additional Information')
                    ->schema([
                        Infolists\Components\TextEntry::make('notes')
                            ->columnSpanFull(),
                    ])
                    ->collapsible(),
            ]);
    }
}