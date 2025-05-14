<?php

namespace App\Filament\Resources\PurchaseOrderResource\Pages;

use App\Filament\Resources\PurchaseOrderResource;
use App\Models\PurchaseOrder;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Resources\Pages\Page;
use Illuminate\Support\Facades\DB;
use Filament\Notifications\Notification;

class ReceivePurchaseOrder extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string $resource = PurchaseOrderResource::class;
    protected static string $view = 'filament.resources.purchase-order-resource.pages.receive-purchase-order';

    public PurchaseOrder $record;
    
    public $data = [];

    public function mount(PurchaseOrder $record): void
    {
        abort_unless($record->canReceiveItems(), 403);
        $this->record = $record;

        // Initialize data with default values
        foreach ($this->record->items as $item) {
            $remaining = $item->quantity - ($item->received_quantity ?? 0);
            if ($remaining > 0) {
                $this->data["quantity_{$item->id}"] = 0;
            }
        }

        $this->form->fill($this->data);
    }

    public function form(Form $form): Form
    {
        $inputs = [];
        foreach ($this->record->items as $item) {
            $remaining = $item->quantity - ($item->received_quantity ?? 0);
            if ($remaining > 0) {
                $inputs[] = \Filament\Forms\Components\TextInput::make("quantity_{$item->id}")
                    ->label($item->product->name)
                    ->numeric()
                    ->default(0)
                    ->minValue(0)
                    ->maxValue($remaining)
                    ->required()
                    ->hint("Remaining: {$remaining} of {$item->quantity}");
            }
        }

        return $form
            ->schema($inputs)
            ->columns(1)
            ->statePath('data');
    }

    public function receive(): void
    {
        $data = $this->form->getState();
        
        DB::beginTransaction();
        
        try {
            $allReceived = true;
            
            foreach ($data as $key => $quantity) {
                if ($quantity > 0) {
                    $itemId = str_replace('quantity_', '', $key);
                    $item = $this->record->items()->with('product')->findOrFail($itemId);
                    
                    $item->received_quantity = ($item->received_quantity ?? 0) + $quantity;
                    $item->save();
                    
                    $item->product->increment('stock', $quantity);
                    
                    if ($item->received_quantity < $item->quantity) {
                        $allReceived = false;
                    }
                }
            }
            
            $this->record->status = $allReceived ? 'completed' : 'partially_received';
            $this->record->save();
            
            DB::commit();
            
            Notification::make()
                ->success()
                ->title('Items received successfully')
                ->send();
            
            $this->redirect($this->getResource()::getUrl('index'));
            
        } catch (\Exception $e) {
            DB::rollBack();
            Notification::make()
                ->danger()
                ->title('Error receiving items')
                ->body($e->getMessage())
                ->send();
        }
    }
}