<?php

namespace App\Filament\Resources\SaleResource\Pages;

use App\Filament\Resources\SaleResource;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateSale extends CreateRecord
{
    protected static string $resource = SaleResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function afterCreate(): void
    {
        $sale = $this->record;
        
        foreach ($sale->items as $item) {
            $item->calculateTotals();
        }

        $sale->calculateTotals();
        $sale->calculateChange();

        $sale->user()->associate(auth()->user());
        $sale->save();
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {

        $subtotal = 0;
        $tax = 0;
        $total = 0;

        foreach ($data['items'] as $item) {
            $itemSubtotal = $item['quantity'] * $item['unit_price'];
            $itemTax = $itemSubtotal * config('pos.tax_rate', 0.12);
            
            $subtotal += $itemSubtotal;
            $tax += $itemTax;
        }

        $total = $subtotal + $tax - ($data['discount'] ?? 0);
        $change = max(0, ($data['payment_amount'] ?? 0) - $total);

        return array_merge($data, [
            'subtotal' => $subtotal,
            'tax' => $tax,
            'total' => $total,
            'change' => $change,
            'status' => 'completed',
            'user_id' => auth()->id(),
        ]);
    }

    protected function beforeFill(): void
    {

        $this->form->registerListeners([
            'items.*.quantity' => [
                function (Get $get, Set $set) {
                    $this->calculateTotals($get, $set);
                },
            ],
            'items.*.unit_price' => [
                function (Get $get, Set $set) {
                    $this->calculateTotals($get, $set);
                },
            ],
            'discount' => [
                function (Get $get, Set $set) {
                    $this->calculateTotals($get, $set);
                },
            ],
            'payment_amount' => [
                function (Get $get, Set $set) {
                    $total = (float) $get('total');
                    $paymentAmount = (float) $get('payment_amount');
                    $set('change', max(0, $paymentAmount - $total));
                },
            ],
        ]);
    }

    private function calculateTotals(Get $get, Set $set): void
    {
        $items = $get('items');
        if (!$items) {
            return;
        }

        $subtotal = 0;
        $tax = 0;

        foreach ($items as $item) {
            $quantity = (int) ($item['quantity'] ?? 0);
            $unitPrice = (float) ($item['unit_price'] ?? 0);
            $itemSubtotal = $quantity * $unitPrice;
            $itemTax = $itemSubtotal * config('pos.tax_rate', 0.12);

            $subtotal += $itemSubtotal;
            $tax += $itemTax;
        }

        $discount = (float) $get('discount');
        $total = $subtotal + $tax - $discount;
        $paymentAmount = (float) $get('payment_amount');

        $set('subtotal', $subtotal);
        $set('tax', $tax);
        $set('total', $total);
        $set('change', max(0, $paymentAmount - $total));
    }
}