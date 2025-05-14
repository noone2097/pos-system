<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PurchaseOrderResource\Pages;
use App\Models\PurchaseOrder;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\URL;
use Filament\Support\Facades\FilamentView;
use Filament\Actions\Action;
use Filament\Support\Enums\ActionSize;
use Illuminate\Support\Facades\Blade;
use App\Filament\RelationManagers\PurchaseOrderItemsRelationManager;

class PurchaseOrderResource extends Resource
{
    protected static ?string $model = PurchaseOrder::class;
    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';
    protected static ?string $navigationGroup = 'Purchase Order Management';
    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\Grid::make()
                            ->schema([
                                Forms\Components\Select::make('supplier_id')
                                    ->relationship('supplier', 'name')
                                    ->required()
                                    ->searchable()
                                    ->preload()
                                    ->columnSpan(2),
                                Forms\Components\DatePicker::make('date')
                                    ->required()
                                    ->default(now()),
                                Forms\Components\TextInput::make('po_number')
                                    ->default(fn () => PurchaseOrder::generatePoNumber())
                                    ->disabled()
                                    ->dehydrated()
                                    ->required(),
                            ])
                            ->columns(4),
                        Forms\Components\Repeater::make('items')
                            ->relationship()
                            ->schema([
                                Forms\Components\Select::make('product_id')
                                    ->label('Product')
                                    ->options(fn () => Product::query()->pluck('name', 'id'))
                                    ->required()
                                    ->searchable()
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, $get, $set) {
                                        if (!$state) {
                                            $set('unit_price', null);
                                            $set('subtotal', null);
                                            return;
                                        }

                                        if ($product = Product::query()->find($state)) {
                                            $set('unit_price', $product->price);
                                            $quantity = $get('quantity') ?? 1;
                                            $set('subtotal', $product->price * $quantity);
                                        } else {
                                            $set('unit_price', null);
                                            $set('subtotal', null);
                                        }
                                    }),
                                Forms\Components\TextInput::make('quantity')
                                    ->numeric()
                                    ->default(1)
                                    ->required()
                                    ->minValue(1)
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, $get, $set) {
                                        $quantity = $state ?? 0;
                                        $unitPrice = $get('unit_price') ?? 0;
                                        $set('subtotal', $quantity * $unitPrice);
                                    }),
                                Forms\Components\TextInput::make('unit_price')
                                    ->numeric()
                                    ->required()
                                    ->prefix('₱')
                                    ->minValue(0.01)
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, $get, $set) {
                                        $unitPrice = $state ?? 0;
                                        $quantity = $get('quantity') ?? 0;
                                        $set('subtotal', $quantity * $unitPrice);
                                    }),
                                Forms\Components\TextInput::make('subtotal')
                                    ->disabled()
                                    ->dehydrated()
                                    ->numeric()
                                    ->prefix('₱'),
                            ])
                            ->columns(4)
                            ->defaultItems(1),
                        Forms\Components\Textarea::make('notes')
                            ->maxLength(65535)
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('po_number')
                    ->label('PO Number')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->copyMessage('PO number copied'),
                Tables\Columns\TextColumn::make('supplier.name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('date')
                    ->date('M d, Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'draft' => 'warning',
                        'pending' => 'primary',
                        'partially_received' => 'info',
                        'completed' => 'success',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('items_count')
                    ->counts('items')
                    ->label('Items'),
                Tables\Columns\TextColumn::make('total_amount')
                    ->money('PHP')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('supplier')
                    ->relationship('supplier', 'name')
                    ->searchable()
                    ->preload(),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->visible(fn (PurchaseOrder $record): bool => $record->canBeEdited()),
                Tables\Actions\Action::make('submit')
                    ->visible(fn (PurchaseOrder $record): bool => $record->status === 'draft')
                    ->icon('heroicon-o-check-circle')
                    ->requiresConfirmation()
                    ->action(function (PurchaseOrder $record) {
                        $record->submit();
                        Notification::make()
                        ->success()
                        ->title('Purchase Order Submitted')
                        ->body('The purchase order has been submitted successfully.')
                        ->send();
                    }),
                Tables\Actions\Action::make('receive')
                    ->visible(fn (PurchaseOrder $record): bool => $record->canReceiveItems())
                    ->icon('heroicon-o-truck')
                    ->url(fn (PurchaseOrder $record): string => static::getUrl('receive', ['record' => $record])),
                Tables\Actions\Action::make('print')
                    ->icon('heroicon-o-printer')
                    ->color('success')
                    ->label('Print PO')
                    ->url(fn (PurchaseOrder $record): string => route('purchase-orders.print', [
                        'purchaseOrder' => $record->id
                    ]))
                    ->openUrlInNewTab(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make()
                    ->visible(fn (PurchaseOrder $record): bool => $record->status === 'draft'),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPurchaseOrders::route('/'),
            'create' => Pages\CreatePurchaseOrder::route('/create'),
            'view' => Pages\ViewPurchaseOrder::route('/{record}'),
            'edit' => Pages\EditPurchaseOrder::route('/{record}/edit'),
            'receive' => Pages\ReceivePurchaseOrder::route('/{record}/receive'),
        ];
    }
}