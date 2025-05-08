<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use Filament\Actions;
use Filament\Forms;
use Filament\Resources\Pages\EditRecord;

class EditProduct extends EditRecord
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('adjust_stock')
                ->form([
                    Forms\Components\TextInput::make('adjustment')
                        ->numeric()
                        ->required()
                        ->label('Stock Adjustment')
                        ->hint('Use positive number to add stock, negative to reduce'),
                    Forms\Components\Textarea::make('notes')
                        ->label('Adjustment Notes')
                        ->rows(2),
                ])
                ->action(function (array $data): void {
                    $this->record->updateStock($data['adjustment']);
                    $this->notify('success', 'Stock adjusted successfully');
                }),
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (empty($data['barcode'])) {
            unset($data['barcode']);
        }

        return $data;
    }
}
