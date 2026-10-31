<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('coffees', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            $table->string('origin')->nullable();
            $table->string('process')->nullable();
            $table->string('form')->nullable();
            $table->string('grade')->nullable();
            $table->decimal('stock', 10, 2)->default(0);
            $table->string('unit')->default('Kg');
            $table->decimal('purchase_price', 12, 2)->default(0);
            $table->text('description')->nullable();
            $table->string('status')->default('Aktif');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('coffees');
    }
};
