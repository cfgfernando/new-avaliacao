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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('financial_account_id')->constrained('financial_accounts');
            $table->foreignId('chart_of_account_id')->constrained('chart_of_accounts');
            $table->foreignId('cost_center_id')->nullable()->constrained('cost_centers')->nullOnDelete();
            
            $table->enum('type', ['income', 'expense', 'transfer']);
            $table->enum('payment_method', ['cash', 'pix', 'transfer', 'credit_card', 'debit_card', 'slip']);
            $table->decimal('amount', 15, 2);
            $table->string('description');
            $table->date('transaction_date');
            $table->enum('status', ['pending', 'paid', 'cancelled'])->default('pending');
            $table->boolean('is_recurrent')->default(false);
            
            // External foreign keys without strict constraints for now
            $table->unsignedBigInteger('weekly_report_id')->nullable()->index();
            $table->unsignedBigInteger('member_id')->nullable()->index();
            $table->unsignedBigInteger('supplier_id')->nullable()->index();
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
