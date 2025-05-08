<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Barcodes</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            @page {
                margin: 0;
                size: auto;
            }
            body {
                margin: 0.5cm;
            }
            .no-print {
                display: none;
            }
            .barcode-container {
                page-break-inside: avoid;
            }
            .barcode-label {
                width: 8cm;
                min-height: 2.5cm;
                margin: 0.2cm;
                padding: 0.2cm;
                float: left;
            }
        }
        .barcode-label {
            width: 8cm;
            min-height: 2.5cm;
            padding: 0.2cm;
            margin: 0.2cm;
            text-align: center;
            float: left;
            border: 1px solid #ddd;
        }
        .barcode-label > div {
            display: flex;
            justify-content: center;
        }
    </style>
</head>
<body class="bg-gray-100 p-4">
    <!-- Print Controls -->
    <div class="no-print mb-4 flex justify-between items-center bg-white p-4 rounded-lg shadow">
        <h1 class="text-2xl font-bold">Print Barcodes</h1>
        <div class="space-x-2">
            <button onclick="window.print()" class="bg-primary-600 text-white px-4 py-2 rounded hover:bg-primary-700">
                Print Barcodes
            </button>
            <a href="{{ url()->previous() }}" class="text-gray-600 hover:text-gray-800">
                Back
            </a>
        </div>
    </div>

    <!-- Barcodes Grid -->
    <div class="bg-white p-4 rounded-lg shadow clear-both">
        @if(isset($products))
            @foreach($products as $product)
                <div class="barcode-container inline-block">
                    <div class="barcode-label rounded">
                        <div class="text-sm font-semibold mb-1">{{ $product->name }}</div>
                        <div class="flex justify-center">{!! $barcodes[$product->id] !!}</div>
                        <div class="text-xs mt-1">{{ $product->barcode }}</div>
                        <div class="text-sm font-bold mt-1">₱{{ number_format($product->price, 2) }}</div>
                    </div>
                </div>
            @endforeach
        @else
            <div class="barcode-container inline-block">
                <div class="barcode-label rounded">
                    <div class="text-sm font-semibold mb-1">{{ $product->name }}</div>
                    <div class="flex justify-center">{!! $barcode !!}</div>
                    <div class="text-xs mt-1">{{ $product->barcode }}</div>
                    <div class="text-sm font-bold mt-1">₱{{ number_format($product->price, 2) }}</div>
                </div>
            </div>
        @endif
    </div>

    <script>
        // Optional: Auto-print when page loads
        /*
        window.onload = function() {
            window.print();
        };
        */
    </script>
</body>
</html>