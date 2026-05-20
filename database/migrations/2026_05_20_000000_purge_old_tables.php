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
        // 1. Remover chaves estrangeiras e colunas legadas da tabela users
        if (Schema::hasColumn('users', 'cell_id')) {
            try {
                Schema::table('users', function (Blueprint $table) {
                    $table->dropForeign(['cell_id']);
                });
            } catch (\Exception $e) {
                // Silencia se a FK não existir
            }
            try {
                Schema::table('users', function (Blueprint $table) {
                    $table->dropColumn('cell_id');
                });
            } catch (\Exception $e) {
                // Silencia
            }
        }

        if (Schema::hasColumn('users', 'node_id')) {
            try {
                Schema::table('users', function (Blueprint $table) {
                    $table->dropForeign(['node_id']);
                });
            } catch (\Exception $e) {
                // Silencia se a FK não existir
            }
            try {
                Schema::table('users', function (Blueprint $table) {
                    $table->dropColumn('node_id');
                });
            } catch (\Exception $e) {
                // Silencia
            }
        }

        // 2. Desabilitar chaves estrangeiras temporariamente para dropar tabelas com segurança
        Schema::disableForeignKeyConstraints();

        // Lista de tabelas legadas a serem removidas permanentemente
        $tables = [
            'visitor_contacts',
            'operational_notifications',
            'journal_entry_items',
            'journal_entry_lines',
            'journal_entries',
            'expenses',
            'fixed_assets',
            'closures',
            'suppliers',
            'banks',
            'weekly_reports',
            'members',
            'visitors',
            'cells',
            'hierarchy_nodes',
            'accounts',
            'chart_of_accounts',
            'cost_centers',
            'financial_accounts',
            'financial_batches',
            'financial_remittances',
            'accounting_audits',
        ];

        foreach ($tables as $table) {
            Schema::dropIfExists($table);
        }

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Esta migration realiza uma purga irreversível para criação do Boilerplate.
    }
};
