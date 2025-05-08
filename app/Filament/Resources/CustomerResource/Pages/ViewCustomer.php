<?php

namespace App\Filament\Resources\CustomerResource\Pages;

use App\Filament\Resources\CustomerResource;
use Filament\Actions;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Enums\IconPosition;
use Illuminate\Support\Facades\DB;

class ViewCustomer extends ViewRecord
{
    protected static string $resource = CustomerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Customer Information')
                    ->schema([
                        Infolists\Components\TextEntry::make('name'),
                    ]),

                Infolists\Components\Section::make('Purchase Statistics')
                    ->schema([
                        Infolists\Components\TextEntry::make('sales_count')
                            ->label('Total Orders')
                            ->state(function ($record) {
                                return $record->sales()->count();
                            }),
                        Infolists\Components\TextEntry::make('total_spent')
                            ->label('Total Spent')
                            ->money('PHP')
                            ->state(function ($record) {
                                return $record->sales()->sum('total');
                            }),
                        Infolists\Components\TextEntry::make('average_order')
                            ->label('Average Order Value')
                            ->money('PHP')
                            ->state(function ($record) {
                                return $record->sales()->avg('total') ?? 0;
                            }),
                        Infolists\Components\TextEntry::make('last_purchase')
                            ->label('Last Purchase')
                            ->date()
                            ->state(function ($record) {
                                return $record->sales()->latest()->first()?->created_at;
                            }),
                    ])
                    ->columns(2),

                Infolists\Components\Section::make('Recent Orders')
                    ->schema([
                        Infolists\Components\RepeatableEntry::make('sales')
                            ->label(false)
                            ->schema([
                                Infolists\Components\TextEntry::make('reference')
                                    ->label('Order Reference')
                                    ->url(fn ($record) => route('filament.loon.resources.sales.view', $record))
                                    ->icon('heroicon-m-arrow-up-right')
                                    ->iconPosition(IconPosition::After),
                                Infolists\Components\TextEntry::make('created_at')
                                    ->label('Date')
                                    ->dateTime('M j, Y : g:i A'),
                                Infolists\Components\TextEntry::make('total')
                                    ->money('PHP'),
                                Infolists\Components\TextEntry::make('status')
                                    ->badge(),
                            ])
                            ->columns(4)
                            ->state(function ($record) {
                                return $record->sales()->latest()->take(5)->get();
                            }),
                    ]),
                
                Infolists\Components\Section::make('Products Purchased')
                    ->schema([
                        Infolists\Components\TextEntry::make('purchased_products')
                            ->label(false)
                            ->state(function ($record) {
                                try {
                                    $saleIds = DB::table('sales')
                                        ->where('customer_id', $record->id)
                                        ->pluck('id')
                                        ->toArray();
                                    
                                    if (empty($saleIds)) {
                                        return "No sales found for this customer.";
                                    }
                                    
                                    $items = DB::table('sale_items')
                                        ->whereIn('sale_id', $saleIds)
                                        ->get();
                                    
                                    if ($items->isEmpty()) {
                                        return "No items found in customer sales.";
                                    }
                                    
                                    $products = [];
                                    foreach ($items as $item) {
                                        $name = $item->product_name ?? 'Unknown';
                                        if (!isset($products[$name])) {
                                            $products[$name] = [
                                                'quantity' => 0,
                                                'total' => 0
                                            ];
                                        }
                                        $products[$name]['quantity'] += (int)$item->quantity;
                                        $products[$name]['total'] += (float)$item->subtotal;
                                    }
                                    
                                    uasort($products, function($a, $b) {
                                        return $b['quantity'] <=> $a['quantity'];
                                    });
                                    
                                    $result = [];
                                    foreach ($products as $name => $data) {
                                        $result[] = "<strong>{$name}</strong> - {$data['quantity']} units (₱" . 
                                                   number_format($data['total'], 2) . ")";
                                    }
                                    
                                    return implode('<br>', $result);
                                } catch (\Exception $e) {
                                    return "Error: " . $e->getMessage();
                                }
                            })
                            ->html(),
                    ]),
            ]);
    }
}