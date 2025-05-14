<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\PurchaseOrder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PurchaseOrderTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_purchase_order(): void
    {
        $admin = User::factory()->create()->assignRole('admin');
        $supplier = Supplier::create([
            'name' => 'Test Supplier',
            'email' => 'test@supplier.com',
            'phone' => '1234567890',
            'address' => 'Test Address',
        ]);
        $product = Product::create([
            'name' => 'Test Product',
            'price' => 100,
            'stock' => 0,
        ]);

        $response = $this->actingAs($admin)->post('/api/purchase-orders', [
            'supplier_id' => $supplier->id,
            'date' => now(),
            'items' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 10,
                    'unit_price' => 90,
                ],
            ],
        ]);

        $response->assertStatus(201);
        
        $this->assertDatabaseHas('purchase_orders', [
            'supplier_id' => $supplier->id,
            'status' => 'draft',
        ]);

        $this->assertDatabaseHas('purchase_order_items', [
            'product_id' => $product->id,
            'quantity' => 10,
            'unit_price' => 90,
            'subtotal' => 900,
        ]);
    }

    public function test_can_receive_purchase_order(): void
    {
        $admin = User::factory()->create()->assignRole('admin');
        $po = PurchaseOrder::create([
            'supplier_id' => Supplier::create([
                'name' => 'Test Supplier',
                'email' => 'test@supplier.com',
                'phone' => '1234567890',
                'address' => 'Test Address',
            ])->id,
            'po_number' => 'PO00000001',
            'date' => now(),
            'status' => 'pending',
        ]);

        $product = Product::create([
            'name' => 'Test Product',
            'price' => 100,
            'stock' => 0,
        ]);

        $po->items()->create([
            'product_id' => $product->id,
            'quantity' => 10,
            'unit_price' => 90,
            'subtotal' => 900,
        ]);

        $response = $this->actingAs($admin)
            ->post("/api/purchase-orders/{$po->id}/receive", [
                'items' => [
                    [
                        'id' => $po->items->first()->id,
                        'receiving_quantity' => 5,
                    ],
                ],
            ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('purchase_orders', [
            'id' => $po->id,
            'status' => 'partially_received',
        ]);

        $this->assertDatabaseHas('purchase_order_items', [
            'id' => $po->items->first()->id,
            'received_quantity' => 5,
        ]);

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'stock' => 5,
        ]);
    }
}