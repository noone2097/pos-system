<?php

namespace App\Filament\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Table;

class PurchaseOrderItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'items';
    protected static ?string $title = 'Items';

    public function form(Form $form): Form
    {
        return $form->schema([]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('product.name')
                    ->label('Product')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('quantity')
                    ->label('Ordered Qty')
                    ->sortable(),
                Tables\Columns\TextColumn::make('received_quantity')
                    ->label('Received Qty')
                    ->sortable(),
                Tables\Columns\TextColumn::make('unit_price')
                    ->money('PHP')
                    ->label('Unit Price')
                    ->sortable(),
                Tables\Columns\TextColumn::make('subtotal')
                    ->money('PHP')
                    ->sortable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Last Receipt')
                    ->dateTime()
                    ->sortable()
                    ->visible(fn ($livewire) => 
                        in_array($livewire->getOwnerRecord()->status, ['partially_received', 'completed'])
                    ),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                // No actions as items are managed in PO create/edit
            ])
            ->actions([
                // No item-level actions
            ])
            ->bulkActions([
                // No bulk actions
            ])
            ->emptyStateHeading('No items')
            ->emptyStateDescription('Items will appear here when added to the purchase order.');
    }
}