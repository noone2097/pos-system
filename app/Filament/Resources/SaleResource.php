<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SaleResource\Pages;
use App\Models\Sale;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class SaleResource extends Resource
{
    protected static ?string $model = Sale::class;

    protected static ?string $navigationIcon = 'fas-money-bill-trend-up';

    protected static ?string $navigationGroup = 'Sales Management';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make()
                            ->schema([
                                Forms\Components\TextInput::make('reference')
                                    ->default(fn () => Sale::generateReference())
                                    ->disabled()
                                    ->dehydrated()
                                    ->required(),
                                Forms\Components\Select::make('customer_id')
                                    ->relationship('customer', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->createOptionForm([
                                        Forms\Components\TextInput::make('name')
                                            ->required()
                                            ->maxLength(255),
                                        Forms\Components\TextInput::make('email')
                                            ->email()
                                            ->maxLength(255),
                                        Forms\Components\TextInput::make('phone')
                                            ->maxLength(255),
                                    ]),
                                Forms\Components\Select::make('payment_method')
                                    ->options(config('pos.payment_methods'))
                                    ->required(),
                                Forms\Components\Textarea::make('notes')
                                    ->rows(3),
                            ])
                            ->columns(2),

                        Forms\Components\Section::make('Items')
                            ->schema([
                                Forms\Components\Repeater::make('items')
                                    ->relationship()
                                    ->schema([
                                        Forms\Components\Select::make('product_id')
                                            ->label('Product')
                                            ->relationship('product', 'name')
                                            ->searchable()
                                            ->preload()
                                            ->afterStateUpdated(function ($state, Forms\Set $set, ?string $old) {
                                                if (!$state || $state === $old) {
                                                    return;
                                                }
                                                
                                                $product = Product::where('id', $state)->first();
                                                if ($product) {
                                                    $set('product_name', $product->name);
                                                    $set('unit_price', $product->price);
                                                    $set('quantity', 1);
                                                }
                                            })
                                            ->required(),
                                        Forms\Components\TextInput::make('product_name')
                                            ->disabled()
                                            ->dehydrated()
                                            ->required(),
                                        Forms\Components\TextInput::make('quantity')
                                            ->numeric()
                                            ->default(1)
                                            ->minValue(1)
                                            ->required(),
                                        Forms\Components\TextInput::make('unit_price')
                                            ->numeric()
                                            ->prefix('₱')
                                            ->disabled()
                                            ->dehydrated()
                                            ->required(),
                                    ])
                                    ->columns(4)
                                    ->deleteAction(
                                        fn (Forms\Components\Actions\Action $action) => $action->requiresConfirmation(),
                                    )
                                    ->reorderable(false)
                                    ->required()
                                    ->minItems(1),
                            ]),
                    ]),

                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Order Summary')
                            ->schema([
                                Forms\Components\TextInput::make('subtotal')
                                    ->prefix('₱')
                                    ->disabled()
                                    ->dehydrated()
                                    ->numeric(),
                                Forms\Components\TextInput::make('tax')
                                    ->prefix('₱')
                                    ->disabled()
                                    ->dehydrated()
                                    ->numeric(),
                                Forms\Components\TextInput::make('discount')
                                    ->prefix('₱')
                                    ->numeric()
                                    ->default(0),
                                Forms\Components\TextInput::make('total')
                                    ->prefix('₱')
                                    ->disabled()
                                    ->dehydrated()
                                    ->numeric(),
                                Forms\Components\TextInput::make('payment_amount')
                                    ->prefix('₱')
                                    ->numeric()
                                    ->required(),
                                Forms\Components\TextInput::make('change')
                                    ->prefix('₱')
                                    ->disabled()
                                    ->dehydrated()
                                    ->numeric(),
                            ]),
                    ]),
            ])
            ->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('reference')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('customer.name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total')
                    ->money('PHP')
                    ->sortable(),
                Tables\Columns\TextColumn::make('payment_method')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'completed' => 'success',
                        'void' => 'danger',
                    }),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Cashier')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('payment_method')
                    ->options(config('pos.payment_methods')),
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'completed' => 'Completed',
                        'void' => 'Void',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\Action::make('print')
                    ->icon('heroicon-m-printer')
                    ->url(fn (Sale $record) => route('sales.print', $record))
                    ->openUrlInNewTab(),
            ])
            ->bulkActions([
                //
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSales::route('/'),
            'create' => Pages\CreateSale::route('/create'),
            'view' => Pages\ViewSale::route('/{record}'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::whereDate('created_at', today())->count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'success';
    }
}