<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Resolve a dependência circular: users precisa existir para cells,
// e cells precisa existir para cell_id em users.
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('cell_id')
                  ->nullable()
                  ->after('role')
                  ->constrained('cells')
                  ->nullOnDelete()
                  ->comment('Célula à qual o usuário pertence (para Líderes)');

            $table->index('cell_id');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['cell_id']);
            $table->dropIndex(['cell_id']);
            $table->dropColumn('cell_id');
        });
    }
};
