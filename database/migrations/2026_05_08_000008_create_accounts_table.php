<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Plano de Contas - compatível com NBC TG / SPED Contábil
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20)->unique()
                  ->comment('Código do Plano de Contas, ex: 1.1.01.001');
            $table->string('name', 200);
            $table->enum('type', ['Asset', 'Liability', 'Equity', 'Revenue', 'Expense'])
                  ->comment('Ativo | Passivo | Patrimônio Líquido | Receita | Despesa');
            $table->enum('nature', ['Debit', 'Credit'])
                  ->comment('Natureza do saldo normal (Débito ou Crédito)');
            $table->foreignId('parent_id')
                  ->nullable()
                  ->constrained('accounts')
                  ->nullOnDelete()
                  ->comment('Conta sintética pai (estrutura em árvore)');
            $table->boolean('is_analytical')
                  ->default(true)
                  ->comment('TRUE = aceita lançamentos; FALSE = apenas consolida');
            $table->decimal('current_balance', 15, 2)->default(0)
                  ->comment('Saldo atual (atualizado via Journal Entries)');
            $table->boolean('active')->default(true);
            $table->timestamps();

            $table->index('type');
            $table->index('parent_id');
            $table->index('is_analytical');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('accounts');
    }
};
