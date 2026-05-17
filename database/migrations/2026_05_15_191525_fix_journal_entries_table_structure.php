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
        // Corrigir a tabela journal_entries
        Schema::table('journal_entries', function (Blueprint $table) {
            // Renomear colunas se existirem
            if (Schema::hasColumn('journal_entries', 'date')) {
                $table->renameColumn('date', 'entry_date');
            }

            // Adicionar colunas faltantes para suportar o modelo JournalEntry
            if (!Schema::hasColumn('journal_entries', 'document_number')) {
                $table->string('document_number', 60)->nullable()->after('description');
            }
            
            if (!Schema::hasColumn('journal_entries', 'source_type')) {
                $table->morphs('source'); // Cria source_type e source_id
            }

            if (!Schema::hasColumn('journal_entries', 'created_by')) {
                $table->foreignId('created_by')->nullable()->constrained('users')->restrictOnDelete();
            }

            if (!Schema::hasColumn('journal_entries', 'is_reversed')) {
                $table->boolean('is_reversed')->default(false);
            }

            if (!Schema::hasColumn('journal_entries', 'reversal_of')) {
                $table->foreignId('reversal_of')->nullable()->constrained('journal_entries')->nullOnDelete();
            }
        });

        // Garantir que a tabela journal_entry_lines exista
        if (!Schema::hasTable('journal_entry_lines')) {
            Schema::create('journal_entry_lines', function (Blueprint $table) {
                $table->id();
                $table->foreignId('journal_entry_id')->constrained('journal_entries')->cascadeOnDelete();
                $table->foreignId('account_id')->constrained('accounts')->restrictOnDelete();
                $table->enum('type', ['Debit', 'Credit']);
                $table->decimal('amount', 15, 2);
                $table->string('description', 255)->nullable();
                $table->timestamps();

                $table->index(['journal_entry_id', 'account_id']);
                $table->index('type');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Não é seguro reverter automaticamente pois pode apagar dados importantes
    }
};
