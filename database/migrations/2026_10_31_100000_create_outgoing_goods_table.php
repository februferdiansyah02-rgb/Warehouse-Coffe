<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('outgoing_goods', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->date('date');
            $table->string('purpose'); // Penjualan, Produksi, Pengiriman, Sampel, Penggunaan Internal, Rusak / Afkir
            $table->string('recipient_name');
            $table->string('reference_no')->nullable();
            $table->string('officer');
            $table->text('note')->nullable();
            $table->decimal('total_kg', 10, 2)->default(0);
            $table->decimal('total_price', 12, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('outgoing_goods');
    }
};
