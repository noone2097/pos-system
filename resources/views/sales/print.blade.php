<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Receipt</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @page {
            size: 80mm 297mm;
            margin: 0;
        }
        
        body {
            font-family: 'Courier New', monospace;
            line-height: 1.2;
        }
        
        .receipt {
            width: 80mm;
            padding: 10mm 5mm;
        }
        
        .receipt-divider {
            border-top: 1px dashed #000;
            margin: 5px 0;
        }
        
        @media print {
            body {
                background: white;
                padding: 0;
                width: 80mm;
            }
            
            .receipt {
                box-shadow: none;
                padding: 5mm;
                margin: 0;
                width: 70mm;
            }
            
            .print-hidden {
                display: none !important;
            }
            
            table {
                width: 100%;
            }
            
            .receipt-header {
                text-align: center;
            }
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="flex min-h-screen">
        
        <div class="receipt bg-white mx-auto my-4 shadow-lg">
            
            <div class="receipt-header text-center">
                <h1 class="text-xl font-bold uppercase">Loon {{ config('pos.receipt.header.company_name') }}</h1>
                <p class="text-sm">Loon, Bohol</p>
                <p class="text-sm">Tel: +63 (038) 555-1234</p>
                <p class="text-sm">loon-pos@email.com</p>
                <div class="receipt-divider"></div>
                <p class="text-sm uppercase font-bold my-2">OFFICIAL RECEIPT</p>
                <div class="receipt-divider"></div>
            </div>

            
            <div class="receipt-details text-sm my-2">
                <div class="flex justify-between">
                    <span>Receipt:</span>
                    <span>{{ $sale->reference }}</span>
                </div>
                <div class="flex justify-between">
                    <span>Date:</span>
                    <span>{{ $sale->created_at->format('M d, Y') }}</span>
                </div>
                <div class="flex justify-between">
                    <span>Time:</span>
                    <span>{{ $sale->created_at->format('h:i A') }}</span>
                </div>
                <div class="flex justify-between">
                    <span>Cashier:</span>
                    <span>{{ $sale->user->name }}</span>
                </div>
                @if($sale->customer)
                    <div class="flex justify-between">
                        <span>Customer:</span>
                        <span>{{ $sale->customer->name }}</span>
                    </div>
                @endif
                <div class="receipt-divider"></div>
            </div>

            
            <div class="receipt-items text-xs my-2">
                <div class="flex justify-between font-bold">
                    <span class="w-36">ITEM</span>
                    <span class="w-8 text-center">QTY</span>
                    <span class="w-16 text-right">PRICE</span>
                    <span class="w-16 text-right">TOTAL</span>
                </div>
                <div class="receipt-divider"></div>
                
                @foreach($sale->items as $item)
                    <div class="flex my-1">
                        <div class="w-36 text-xs leading-tight pr-1">{{ $item->product_name }}</div>
                        <span class="w-8 text-center">{{ $item->quantity }}</span>
                        <span class="w-16 text-right">₱{{ number_format($item->unit_price, 2) }}</span>
                        <span class="w-16 text-right">₱{{ number_format($item->subtotal, 2) }}</span>
                    </div>
                @endforeach
                
                <div class="receipt-divider"></div>
            </div>

            
            <div class="receipt-totals text-sm">
                <div class="flex justify-between">
                    <span>Subtotal:</span>
                    <span>₱{{ number_format($sale->subtotal, 2) }}</span>
                </div>
                @if($sale->tax > 0)
                    <div class="flex justify-between">
                        <span>Tax:</span>
                        <span>₱{{ number_format($sale->tax, 2) }}</span>
                    </div>
                @endif
                @if($sale->discount > 0)
                    <div class="flex justify-between">
                        <span>Discount:</span>
                        <span>₱{{ number_format($sale->discount, 2) }}</span>
                    </div>
                @endif
                <div class="receipt-divider"></div>
                <div class="flex justify-between font-bold">
                    <span>TOTAL:</span>
                    <span>₱{{ number_format($sale->total, 2) }}</span>
                </div>
                <div class="flex justify-between">
                    <span>{{ strtoupper($sale->payment_method) }}:</span>
                    <span>₱{{ number_format($sale->payment_amount, 2) }}</span>
                </div>
                <div class="flex justify-between">
                    <span>CHANGE:</span>
                    <span>₱{{ number_format($sale->change, 2) }}</span>
                </div>
                <div class="receipt-divider"></div>
            </div>

            
            <div class="receipt-footer text-center text-xs my-3">
                <p class="font-bold">{{ config('pos.receipt.footer.thank_you_message') }}</p>
                <p class="mt-1">{{ config('pos.receipt.footer.return_policy') }}</p>
                <p class="mt-3 text-xs">This receipt serves as your official sales invoice</p>
            </div>
        </div>

        
        <div class="fixed bottom-0 left-0 right-0 print-hidden bg-white p-4 border-t shadow-lg flex justify-between px-8">
            <div>
                <button onclick="window.print()" class="bg-primary-600 text-white px-6 py-2 rounded-lg hover:bg-primary-700 font-medium">
                    Print Receipt
                </button>
            </div>
            <div>
                <a href="{{ route('filament.loon.pages.pos-terminal') }}" class="bg-gray-600 text-white px-6 py-2 rounded-lg hover:bg-gray-700 font-medium">
                    Back to POS
                </a>
            </div>
        </div>
    </div>
</body>
</html>