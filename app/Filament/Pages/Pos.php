<?php

namespace App\Filament\Pages;

use App\Models\Customer;
use App\Models\Product;
use App\Models\Sale;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Str;
use Livewire\Attributes\On;

class Pos extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'mdi-point-of-sale';

    protected static ?string $navigationLabel = 'POS Terminal';

    protected static string $view = 'filament.pages.pos';

    protected static ?string $slug = 'pos-terminal';

    protected static ?string $title = '';

    protected static ?int $navigationSort = 1;

    public ?array $cart = [];

    public $scannedBarcode = '';

    public $payment_method = null;

    public $payment_amount = 0;

    public $total = 0;

    private $lastScannedBarcode = '';

    private $lastScannedTime = 0;

    private const SCAN_COOLDOWN = 2;

    private $modalOpen = false;

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->hasRole('cashier') && ! auth()->user()->hasRole('admin');
    }

    protected function getHeaderActions(): array
    {
        return [];
    }

    protected function getViewData(): array
    {
        return [
            'pageTitle' => 'POS Terminal',
        ];
    }

    public function mount(): void
    {
        if (! auth()->user()->hasRole('cashier')) {
            redirect()->to('/loon');
        }

        $this->cart = session('cart', []);

        $this->cart = collect($this->cart)->map(function ($item) {
            if (! isset($item['unique_id'])) {
                $item['unique_id'] = (string) Str::uuid();
            }

            return $item;
        })->toArray();

        session(['cart' => $this->cart]);

        $this->calculateTotal();
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('payment_method')
                    ->options(config('pos.payment_methods'))
                    ->extraAttributes(['class' => 'mb-0'])
                    ->required(),

                TextInput::make('payment_amount')
                    ->numeric()
                    ->prefix('₱')
                    ->required()
                    ->extraAttributes(['class' => 'mb-0'])
                    ->afterStateUpdated(function () {
                        $this->calculateChange();
                    }),
            ])
            ->columns(1);
    }

    #[On('handleBarcode')]
    public function handleBarcode($barcode)
    {
        $currentTime = time();

        $this->modalOpen = true;

        $processedBarcodes = session('processed_barcodes', []);

        if (isset($processedBarcodes[$barcode])) {
            $lastProcessed = $processedBarcodes[$barcode];
            $timeDiff = $currentTime - $lastProcessed;

            if ($timeDiff < 10) {
                $this->dispatch('scan-complete', [
                    'success' => false,
                    'message' => 'Already processed this barcode',
                    'keep_scanner_open' => true,
                    'keep_modal_open' => true,
                ]);

                return;
            }
        }

        $processedBarcodes[$barcode] = $currentTime;
        session(['processed_barcodes' => $processedBarcodes]);

        $this->lastScannedBarcode = $barcode;
        $this->lastScannedTime = $currentTime;

        $product = (new Product)->newQuery()
            ->where('barcode', $barcode)
            ->first();

        if (! $product) {
            Notification::make()
                ->title('Product not found')
                ->danger()
                ->send();

            $this->dispatch('scan-complete', [
                'success' => false,
                'message' => 'Product not found',
                'keep_scanner_open' => true,
                'keep_modal_open' => true,
            ]);

            return;
        }

        $this->addToCartOnce($product);

        $this->dispatch('scan-complete', [
            'success' => true,
            'message' => 'Product added to cart',
            'product' => [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
            ],
            'keep_scanner_open' => true,
            'keep_modal_open' => true,
        ]);
    }

    public function addToCartOnce($product)
    {
        $uniqueId = (string) Str::uuid();

        $cartItem = collect($this->cart)->first(function ($item) use ($product) {
            return $item['id'] === $product->id;
        });

        if ($cartItem) {
            $this->cart = collect($this->cart)
                ->map(function ($item) use ($product) {
                    if ($item['id'] === $product->id) {
                        $item['quantity'] += 1;
                        $item['subtotal'] = $item['price'] * $item['quantity'];
                    }

                    return $item;
                })
                ->toArray();
        } else {
            $this->cart[] = [
                'unique_id' => $uniqueId,
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => 1,
                'subtotal' => $product->price,
            ];
        }

        $this->calculateTotal();
        session(['cart' => $this->cart]);

        $this->modalOpen = true;

        Notification::make()
            ->title('Product added to cart')
            ->success()
            ->send();

        $this->dispatch('cart-updated', [
            'cart_count' => count($this->cart),
            'total' => $this->total,
            'keep_scanner_open' => true,
            'keep_modal_open' => true,
        ]);
    }

    public function addToCart($product)
    {
        $uniqueId = (string) Str::uuid();

        $cartItem = collect($this->cart)->first(function ($item) use ($product) {
            return $item['id'] === $product->id;
        });

        if ($cartItem) {
            $this->incrementQuantity($product->id);
        } else {
            $this->cart[] = [
                'unique_id' => $uniqueId,
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => 1,
                'subtotal' => $product->price,
            ];
        }

        $this->calculateTotal();
        session(['cart' => $this->cart]);

        $this->dispatch('cart-updated', [
            'cart_count' => count($this->cart),
            'total' => $this->total,
            'keep_scanner_open' => true,
        ]);

        Notification::make()
            ->title('Product added to cart')
            ->success()
            ->send();
    }

    public function incrementQuantity($uniqueIdOrProductId)
    {
        $this->updateCartItemQuantity($uniqueIdOrProductId, 1);
    }

    public function decrementQuantity($uniqueIdOrProductId)
    {
        $this->updateCartItemQuantity($uniqueIdOrProductId, -1);
    }

    public function removeFromCart($uniqueIdOrProductId)
    {
        $this->cart = collect($this->cart)
            ->filter(function ($item) use ($uniqueIdOrProductId) {
                return $item['unique_id'] !== $uniqueIdOrProductId && $item['id'] !== $uniqueIdOrProductId;
            })
            ->values()
            ->toArray();

        $this->calculateTotal();
        session(['cart' => $this->cart]);

        $this->dispatch('cart-updated', [
            'cart_count' => count($this->cart),
            'total' => $this->total,
            'keep_scanner_open' => false,
        ]);

        Notification::make()
            ->title('Product removed from cart')
            ->success()
            ->send();
    }

    private function updateCartItemQuantity($uniqueIdOrProductId, $change)
    {
        $this->cart = collect($this->cart)
            ->map(function ($item) use ($uniqueIdOrProductId, $change) {
                if ($item['unique_id'] === $uniqueIdOrProductId || $item['id'] === $uniqueIdOrProductId) {
                    $newQuantity = $item['quantity'] + $change;
                    if ($newQuantity > 0) {
                        return [
                            ...$item,
                            'quantity' => $newQuantity,
                            'subtotal' => $item['price'] * $newQuantity,
                        ];
                    }

                    return null;
                }

                return $item;
            })
            ->filter()
            ->values()
            ->toArray();

        $this->calculateTotal();
        session(['cart' => $this->cart]);

        $this->dispatch('cart-updated', [
            'cart_count' => count($this->cart),
            'total' => $this->total,
            'keep_scanner_open' => true,
        ]);
    }

    private function calculateTotal()
    {
        $this->total = collect($this->cart)->sum('subtotal');
    }

    private function calculateChange()
    {
        return max(0, $this->payment_amount - $this->total);
    }

    public function checkout()
    {
        $this->validate([
            'payment_method' => 'required',
            'payment_amount' => 'required|numeric|min:'.$this->total,
        ]);

        $customer = Customer::createDefault();

        $sale = Sale::create([
            'reference' => Sale::generateReference(),
            'customer_id' => $customer->id,
            'user_id' => auth()->id(),
            'payment_method' => $this->payment_method,
            'payment_amount' => $this->payment_amount,
            'subtotal' => $this->total,
            'tax' => 0,
            'discount' => 0,
            'total' => $this->total,
            'change' => $this->calculateChange(),
            'status' => 'completed',
        ]);

        foreach ($this->cart as $item) {
            $sale->items()->create([
                'product_id' => $item['id'],
                'product_name' => $item['name'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['price'],
                'subtotal' => $item['subtotal'],
            ]);

            $product = (new Product)->newQuery()->find($item['id']);
            if ($product) {
                $product->decrement('stock', $item['quantity']);
            }
        }

        Notification::make()
            ->title('Sale completed successfully!')
            ->body("Customer: {$customer->name}")
            ->success()
            ->send();

        $this->cart = [];
        session()->forget('cart');
        $this->reset(['payment_method', 'payment_amount', 'total']);

        return redirect()->route('sales.print', $sale);
    }

    public function clearCart()
    {
        $this->cart = [];
        session()->forget('cart');
        $this->total = 0;

        session()->forget('processed_barcodes');

        Notification::make()
            ->title('Cart cleared')
            ->success()
            ->send();
    }

    public function searchBarcode()
    {
        if (! empty($this->scannedBarcode)) {
            $this->handleBarcode($this->scannedBarcode);
            $this->scannedBarcode = '';
        }
    }

    protected function getScripts(): array
    {
        return [
            'https://cdn.jsdelivr.net/npm/quagga@0.12.1/dist/quagga.min.js',
        ];
    }

    #[On('modalClosed')]
    public function handleModalClosed($data = [])
    {
        $this->modalOpen = false;

        session()->forget('temp_scan_session');

        if (empty($data['userClosed'])) {
            $this->dispatch('checkScanningState');
        }
    }

    #[On('fixModalClose')]
    public function fixModalClose()
    {
        if ($this->modalOpen) {
            $this->dispatch('forceReopenModal');
        } else {
            $this->dispatch('ensureModalClosed');
        }
    }
}
