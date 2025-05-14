<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Purchase Order #{{ $purchaseOrder->po_number }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            line-height: 1.4;
            margin: 0;
            padding: 20px;
        }
        .org-header {
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .org-header-content {
            width: 100%;
            border-collapse: collapse;
        }
        .org-logo {
            width: 100px;
            height: auto;
        }
        .org-details {
            padding-left: 15px;
            vertical-align: middle;
        }
        .org-name {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 2px;
            color: #333;
            text-transform: uppercase;
        }
        .org-address {
            font-size: 12px;
            margin-bottom: 0;
            line-height: 1.3;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header h2 {
            margin: 0;
            padding: 10px;
            background-color: #f5f5f5;
            border: 1px solid #ddd;
            font-size: 20px;
        }
        .details {
            margin-bottom: 20px;
        }
        .details table {
            width: 100%;
        }
        .details td {
            padding: 5px;
        }
        .items {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .items th, .items td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .items th {
            background-color: #f5f5f5;
        }
        .total {
            text-align: right;
            margin-top: 20px;
        }
        .signatures {
            margin-top: 100px;
            width: 100%;
        }
        .signature-container {
            float: left;
            width: 45%;
            text-align: center;
        }
        .signature-container.right {
            float: right;
        }
        .signature-line {
            border-top: 1px solid #000;
            width: 200px;
            margin: 0 auto;
            margin-bottom: 5px;
        }
        .signature-title {
            font-weight: normal;
            margin: 0;
            font-size: 12px;
        }
        .signature-name {
            font-weight: bold;
            margin: 0;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="org-header">
        <table class="org-header-content" cellpadding="0" cellspacing="0">
            <tr>
                <td width="110">
                    @php
                        $logoPath = public_path('images/LSP-MPC.jpg');
                        $logoExists = file_exists($logoPath);
                    @endphp
                    
                    @if($logoExists)
                        <img src="{{ $logoPath }}" alt="LOON SERVICE PROVIDERS MPC" class="org-logo">
                    @else
                        <!-- Logo not found, display text instead -->
                        <div style="text-align: center; font-weight: bold; font-size: 12px;">
                            LOGO<br>NOT<br>AVAILABLE
                        </div>
                    @endif
                </td>
                <td>
                    <div class="org-details">
                        <div class="org-name">Loon Service Providers Multi-Purpose Cooperative</div>
                        <div class="org-address">2nd floor Mercado De Loon, Cogon Norte, Loon, Bohol, Philippines 6327</div>
                        <div class="org-address">Tel: 0929 890 3442 | Email: info@loonserviceproviders.coop</div>
                        <div class="org-address">Facebook: facebook.com/people/Loon-Service-Providers-Multi-Purpose-Cooperative/100075979445153</div>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <div class="header">
        <h2>PURCHASE ORDER</h2>
    </div>

    <div class="details">
        <table>
            <tr>
                <td><strong>PO Number:</strong></td>
                <td>{{ $purchaseOrder->po_number }}</td>
                <td><strong>Date:</strong></td>
                <td>{{ $purchaseOrder->date->format('M d, Y') }}</td>
            </tr>
            <tr>
                <td><strong>Supplier:</strong></td>
                <td>{{ $purchaseOrder->supplier->name }}</td>
                <td><strong>Status:</strong></td>
                <td>{{ ucfirst($purchaseOrder->status) }}</td>
            </tr>
        </table>
    </div>

    <table class="items">
        <thead>
            <tr>
                <th>Product</th>
                <th>Quantity</th>
                <th>Unit Price</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($purchaseOrder->items as $item)
            <tr>
                <td>{{ $item->product->name }}</td>
                <td>{{ $item->quantity }}</td>
                <td>₱ {{ number_format($item->unit_price, 2, '.', ',') }}</td>
                <td>₱ {{ number_format($item->subtotal, 2, '.', ',') }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" style="text-align: right;"><strong>Total Amount:</strong></td>
                <td><strong>₱ {{ number_format($purchaseOrder->total_amount, 2, '.', ',') }}</strong></td>
            </tr>
        </tfoot>
    </table>

    @if($purchaseOrder->notes)
    <div class="notes">
        <strong>Notes:</strong><br>
        {{ $purchaseOrder->notes }}
    </div>
    @endif

    <div class="signatures">
        <div class="signature-container">
            <div class="signature-name">{{ $preparedBy }}</div>
            <div class="signature-line"></div>
            <p class="signature-title">Prepared by</p>
        </div>
        <div class="signature-container right">
            <div class="signature-name">{{ $approvedBy }}</div>
            <div class="signature-line"></div>
            <p class="signature-title">Approved by</p>
        </div>
        <div style="clear: both;"></div>
    </div>
</body>
</html>