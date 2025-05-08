<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Milon\Barcode\DNS1D;

class BarcodeController extends Controller
{
    public function print(Product $product)
    {
        // Generate EAN-13 barcode
        $barcode = (new DNS1D)->getBarcodeHTML($product->barcode, 'EAN13');

        // Force reload the product to ensure fresh data
        $product = Product::find($product->id);

        return view('barcodes.print', [
            'product' => $product,
            'barcode' => $barcode,
        ]);
    }

    public function printMultiple(Request $request)
    {
        // Force reload products to ensure fresh data
        $products = Product::whereIn('id', $request->input('products', []))
            ->get();

        $barcodes = [];

        foreach ($products as $product) {
            $barcodes[$product->id] = (new DNS1D)->getBarcodeHTML($product->barcode, 'EAN13');
        }

        return view('barcodes.print', [
            'products' => $products,
            'barcodes' => $barcodes,
        ]);
    }
}
