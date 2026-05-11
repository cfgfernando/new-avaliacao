<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Fase 2: Supervisores precisam de um nó hierárquico próprio (Área/Setor).
// Líderes usam cell_id; Supervisores usam node_id.
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('node_id')
                  ->nullable()
                  ->after('cell_id')
                  ->constrained('hierarchy_nodes')
                  ->nullOnDelete()
                  ->comment('Nó hierárquico gerenciado (para Supervisores de Área/Setor)');

            $table->index('node_id');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['node_id']);
            $table->dropIndex(['node_id']);
            $table->dropColumn('node_id');
        });
    }
};
