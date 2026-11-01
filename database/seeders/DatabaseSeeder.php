<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Supplier;
use App\Models\User;
use App\Models\WarehouseProfile;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        Category::create(['name' => 'Arabica', 'description' => 'Biji kopi arabica']);
        Category::create(['name' => 'Robusta', 'description' => 'Biji kopi robusta']);
        Category::create(['name' => 'Liberica', 'description' => 'Biji kopi liberica']);

        Supplier::create(['name' => 'PT Kopi Nusantara', 'contact' => '08123456789', 'email' => 'info@kopinusantara.id', 'address' => 'Bandung']);
        Supplier::create(['name' => 'CV Biji Jaya', 'contact' => '08987654321', 'email' => 'sales@bijijaya.id', 'address' => 'Jakarta']);

        WarehouseProfile::create([
            'name' => 'KOPI.ONE Warehouse',
            'company_name' => 'PT Kopi Nusantara',
            'address' => 'Jakarta',
            'phone' => '081234567890',
            'email' => 'info@kopione.com',
            'pic' => 'Ferdi',
            'description' => 'Gudang penyimpanan biji kopi',
        ]);
    }
}
