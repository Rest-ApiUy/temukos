<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_code')->unique(); // e.g. TK-AB12C
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            
            // Criteria & specs
            $table->string('city');
            $table->string('budget');
            $table->date('movein_date')->nullable();
            $table->string('near')->nullable();
            $table->string('distance')->nullable();
            $table->json('features')->nullable();
            $table->string('gender')->nullable();
            $table->string('priority')->nullable();
            $table->text('notes')->nullable();
            $table->json('service_extras')->nullable();
            $table->boolean('pickup')->default(false);
            $table->string('pickup_point')->nullable();

            // Pricing & package
            $table->string('package_name')->default('Layanan TemuKost');
            $table->unsignedInteger('price')->default(500000);
            $table->string('payment_status')->default('pending'); // pending, paid
            $table->string('status')->default('Menunggu pembayaran'); // Menunggu pembayaran, Kriteria ditinjau, Pencarian berjalan, Pilihan dibagikan, Selesai, Dibatalkan

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
