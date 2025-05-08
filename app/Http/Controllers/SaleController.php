<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use Illuminate\Http\Request;

class SaleController extends Controller
{
    public function print(Sale $sale)
    {
        // Check if the user has permission to view this sale
        if (!auth()->user()->hasRole(['admin', 'cashier'])) {
            abort(403);
        }

        return view('sales.print', compact('sale'));
    }
}