<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('incoming_good_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('incoming_good_id')->constrained('incoming_goods')->cascadeOnDelete();
            $table->foreignId('coffee_id')->nullable()->constrained('coffees')->nullOnDelete();
            $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->string('name');
            $table->decimal('qty', 10, 2);
            $table->decimal('price', 12, 2);
            $table->decimal('subtotal', 12, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('incoming_good_items');
    }
};