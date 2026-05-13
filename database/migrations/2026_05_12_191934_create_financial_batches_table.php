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
        Schema::create('financial_batches', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->enum('status', ['pending', 'confirmed', 'divergent'])->default('pending');
            $table->decimal('declared_amount', 15, 2)->default(0);
            $table->decimal('confirmed_amount', 15, 2)->nullable();
            $table->timestamp('opened_at')->useCurrent();
            $table->timestamp('closed_at')->nullable();
            $table->foreignId('closed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('financial_batches');
    }
};
