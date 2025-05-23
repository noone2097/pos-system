<?php

use App\Http\Controllers\BarcodeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\SaleController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // Sales routes
    Route::get('/sales/{sale}/print', [SaleController::class, 'print'])->name('sales.print');

    // Barcode routes
    Route::get('/products/{product}/barcode', [BarcodeController::class, 'print'])->name('products.barcode.print');
    Route::post('/products/barcodes/print', [BarcodeController::class, 'printMultiple'])->name('products.barcodes.print');

    // Purchase Order routes
    Route::get('/purchase-orders/{purchaseOrder}/print', [PurchaseOrderController::class, 'print'])
        ->name('purchase-orders.print');
});
