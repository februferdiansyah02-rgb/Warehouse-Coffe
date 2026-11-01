<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Item;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TempDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_loads_with_real_low_stock_and_transactions(): void
    {
        $user = User::factory()->create();
        $category = Category::create(['name' => 'Arabika']);
        $supplier = Supplier::create(['name' => 'CV Kopi Sejahtera']);

        // Item stok aman
        Item::create([
            'item_name' => 'Kopi Aman',
            'category_id' => $category->id,
            'supplier_id' => $supplier->id,
            'stock_kg' => 100,
            'minimum_stock' => 10,
            'price_per_kg' => 50000,
        ]);

        // Item stok menipis (di bawah minimum)
        Item::create([
            'item_name' => 'Kopi Sedikit',
            'category_id' => $category->id,
            'supplier_id' => $supplier->id,
            'stock_kg' => 3,
            'minimum_stock' => 10,
            'price_per_kg' => 60000,
        ]);

        $response = $this
            ->actingAs($user)
            ->get('/dashboard');

        $response->assertOk();
        $response->assertViewHas('items');
        $response->assertViewHas('lowStockItems');
        $response->assertViewHas('recentTransactions');
        $response->assertViewHas('nettoBulanIni');

        $this->assertCount(1, $response->viewData('lowStockItems'));
        $this->assertSame('Kopi Sedikit', $response->viewData('lowStockItems')->first()->item_name);
    }
}
