<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('outgoing_good_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('outgoing_good_id')->constrained('outgoing_goods')->cascadeOnDelete();
            $table->foreignId('coffee_id')->nullable()->constrained('coffees')->nullOnDelete();
            $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->string('name'); // Nama kopi
            $table->decimal('available_stock', 10, 2); // Stok sebelum keluar
            $table->decimal('qty', 10, 2); // Jumlah keluar
            $table->string('unit')->default('Kg');
            $table->decimal('price', 12, 2)->default(0); // Harga jual per kg
            $table->decimal('subtotal', 12, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('outgoing_good_items');
    }
};
