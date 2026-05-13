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
        Schema::create('financial_remittances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('batch_id')->nullable()->constrained('financial_batches')->nullOnDelete();
            $table->morphs('remittable');
            $table->decimal('amount', 15, 2);
            $table->enum('type', ['tithe', 'offer', 'donation', 'other']);
            $table->enum('status', ['pending', 'processed', 'cancelled'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('financial_remittances');
    }
};
