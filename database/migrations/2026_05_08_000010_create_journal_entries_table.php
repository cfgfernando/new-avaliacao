<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Partida Dobrada (Double-Entry Bookkeeping) - Conformidade NBC TG 1000 / SPED
return new class extends Migration
{
    public function up(): void
    {
        // Cabeçalho do Lançamento Contábil
        Schema::create('journal_entries', function (Blueprint $table) {
            $table->id();
            $table->string('reference', 30)->unique()
                  ->comment('Número único do lançamento, ex: LCT-2026-0001');
            $table->date('entry_date')
                  ->comment('Data de competência do lançamento');
            $table->string('description', 255)
                  ->comment('Histórico contábil do lançamento');
            $table->string('document_number', 60)->nullable()
                  ->comment('NF, Recibo ou número de referência externo');
            $table->morphs('source');
            $table->foreignId('created_by')
                  ->constrained('users')
                  ->restrictOnDelete();
            $table->boolean('is_reversed')->default(false)
                  ->comment('Indica se este lançamento foi estornado');
            $table->foreignId('reversal_of')
                  ->nullable()
                  ->constrained('journal_entries')
                  ->nullOnDelete()
                  ->comment('ID do lançamento original que este estorna');
            $table->timestamps();

            $table->index('entry_date');
            $table->index('is_reversed');
        });

        // Linhas do Lançamento - cada lançamento deve ter débitos = créditos (Partida Dobrada)
        Schema::create('journal_entry_lines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('journal_entry_id')
                  ->constrained('journal_entries')
                  ->cascadeOnDelete();
            $table->foreignId('account_id')
                  ->constrained('accounts')
                  ->restrictOnDelete();
            $table->enum('type', ['Debit', 'Credit'])
                  ->comment('D = Débito | C = Crédito');
            $table->decimal('amount', 15, 2)
                  ->comment('Valor sempre positivo; o tipo determina o lado');
            $table->string('description', 255)->nullable()
                  ->comment('Histórico específico da linha (opcional)');
            $table->timestamps();

            $table->index(['journal_entry_id', 'account_id']);
            $table->index('type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('journal_entry_lines');
        Schema::dropIfExists('journal_entries');
    }
};
