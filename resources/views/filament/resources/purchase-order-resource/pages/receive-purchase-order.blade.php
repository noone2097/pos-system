<x-filament-panels::page>
    <div>
        <h2 class="text-lg font-medium">Purchase Order: {{ $record->po_number }}</h2>
        <p class="mt-1 text-sm text-gray-500">Supplier: {{ $record->supplier->name }}</p>
    </div>

    <x-filament-panels::form wire:submit="receive">
        {{ $this->form }}

        <x-filament::button type="submit" color="success" class="mt-4">
            Save Receipt
        </x-filament::button>
    </x-filament-panels::form>
</x-filament-panels::page>