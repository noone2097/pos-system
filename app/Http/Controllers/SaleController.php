<?php

namespace App\Http\Controllers;

use App\Models\Sale;

class SaleController extends Controller
{
    public function print(Sale $sale)
    {
        // Check if the user has permission to view this sale
        if (! auth()->user()->hasRole(['admin', 'cashier'])) {
            abort(403);
        }

        return view('sales.print', compact('sale'));
    }
}
