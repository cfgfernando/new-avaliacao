<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('account_id')
                  ->constrained('accounts')
                  ->restrictOnDelete()
                  ->comment('Conta de despesa do Plano de Contas');
            $table->foreignId('node_id')
                  ->nullable()
                  ->constrained('hierarchy_nodes')
                  ->nullOnDelete()
                  ->comment('Nó hierárquico de origem (Área/Setor/Rede)');
            $table->foreignId('cell_id')
                  ->nullable()
                  ->constrained('cells')
                  ->nullOnDelete()
                  ->comment('Célula de origem (se aplicável)');
            $table->string('description', 255);
            $table->decimal('amount', 12, 2);
            $table->date('expense_date');
            $table->string('beneficiary', 150)->nullable()
                  ->comment('Fornecedor / Beneficiário do pagamento');
            $table->string('document_number', 60)->nullable()
                  ->comment('Número da Nota Fiscal ou Recibo');
            $table->enum('payment_method', ['Cash', 'Pix', 'TED', 'DOC', 'Boleto', 'Card', 'Other'])
                  ->nullable();
            $table->foreignId('requested_by')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete()
                  ->comment('Usuário que solicitou a despesa');
            $table->foreignId('approved_by')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete()
                  ->comment('Usuário (Tesoureiro/Admin) que aprovou');
            $table->timestamp('approved_at')->nullable();
            $table->enum('status', ['Pending', 'Approved', 'Rejected', 'Paid'])
                  ->default('Pending');
            $table->string('receipt_path')->nullable()
                  ->comment('Caminho do comprovante de pagamento (storage)');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('status');
            $table->index('expense_date');
            $table->index(['node_id', 'cell_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
